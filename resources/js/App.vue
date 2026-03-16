<script setup>
// Importeer ref voor reactieve state van Vue
import { ref } from 'vue'

// Input veld voor nieuwe todo
const newTodo = ref('')

// Input veld voor nieuwe map
const newMap = ref('')

// Array met alle todos in de hoofd lijst
const todos = ref([])

// Array met alle mappen
const maps = ref([])

// Unieke ID teller voor todos
const todoIdCounter = ref(0)

// Unieke ID teller voor mappen
const mapIdCounter = ref(0)

// Huidig getrokken todo (voor drag & drop)
const draggedTodo = ref(null)

// Waar de todo vandaan komt (main list of map id)
const draggedFromMap = ref(null)

// Functie om een nieuwe todo toe te voegen
const addTodo = () => {
  if (!newTodo.value.trim()) return
  todos.value.push({
  id: todoIdCounter.value++,
  text: newTodo.value,
  completed: false,
  dragging: false // toegevoegde property
})
  newTodo.value = ''
}

// Functie om een nieuwe map toe te voegen
const addMap = () => {
  if (!newMap.value.trim()) return
  maps.value.push({
    id: mapIdCounter.value++,
    text: newMap.value,
    completed: false,
    todos: []
  })
  newMap.value = ''
}

// Verwijder todo uit hoofd lijst
const removeTodo = (id) => {
  todos.value = todos.value.filter(t => t.id !== id)
}

// Verwijder map
const removeMap = (id) => {
  maps.value = maps.value.filter(m => m.id !== id)
}

// Toggle voltooid status van hoofd todo
const toggleTodo = (id) => {
  const todo = todos.value.find(t => t.id === id)
  if (todo) todo.completed = !todo.completed
}

// Toggle voltooid status van map
const toggleMap = (id) => {
  const map = maps.value.find(m => m.id === id)
  if (map) map.completed = !map.completed
}

// Toggle voltooid status van todo in map
const toggleTodoInMap = (map, todo) => {
  const t = map.todos.find(t => t.id === todo.id)
  if (t) t.completed = !t.completed
}

// Start drag: sla de todo op en van waar deze komt
const startDrag = (todo, event, fromMapId = null) => {
  draggedTodo.value = todo
  draggedFromMap.value = fromMapId
  todo.dragging = true

  // Maak een clone van het element voor de drag image
  const dragGhost = event.target.cloneNode(true)
  dragGhost.style.opacity = '1'           // volledig zichtbaar
  dragGhost.style.position = 'absolute'   // positioneer correct
  dragGhost.style.top = '-1000px'         // uit het zicht
  dragGhost.style.left = '-1000px'
  document.body.appendChild(dragGhost)

  // Gebruik de clone als drag image
  event.dataTransfer.setDragImage(dragGhost, 0, 0)

  // Verwijder de clone na korte tijd zodat het DOM niet volloopt
  setTimeout(() => {
    document.body.removeChild(dragGhost)
  }, 0)
}
const endDrag = (todo) => {
  todo.dragging = false // verwijder drag markering
  draggedTodo.value = null
  draggedFromMap.value = null
}

// Drop functie: voeg todo toe aan map
const dropTodo = (map) => {
  if (!draggedTodo.value) return

  // Verwijder todo van waar deze vandaan kwam
  if (draggedFromMap.value === null) {
    todos.value = todos.value.filter(t => t.id !== draggedTodo.value.id)
  } else {
    const fromMap = maps.value.find(m => m.id === draggedFromMap.value)
    if (fromMap) {
      fromMap.todos = fromMap.todos.filter(t => t.id !== draggedTodo.value.id)
    }
  }

  // Voeg todo toe aan deze map
  draggedTodo.value.dragging = false
  map.todos.push(draggedTodo.value)

  // Reset drag state
  draggedTodo.value = null
  draggedFromMap.value = null
}
</script>

<template>
<div class="layout">

  <div class="todo-container">
    <div class="app">
      <h1>To-Do List</h1>

      <div class="input-section">
        <input 
          v-model="newTodo" 
          @keyup.enter="addTodo"
          placeholder="Voeg een taak toe..."
          class="todo-input"
        />
        <button @click="addTodo" class="add-button">Toevoegen</button>
      </div>

      <ul class="todo-list">
        <li 
          v-for="todo in todos"
          :key="todo.id"
          class="todo-item"
          :class="{ completed: todo.completed, dragging: todo.dragging }"
          draggable="true"
          @dragstart="startDrag(todo, $event, null)"
          @dragend="endDrag(todo)"
        >
          <input
            type="checkbox"
            :checked="todo.completed"
            @change="toggleTodo(todo.id)"
            class="todo-checkbox"
          />
          <span class="todo-text">{{ todo.text }}</span>
          <button @click="removeTodo(todo.id)" class="delete-button">x</button>
        </li>
      </ul>

      <p v-if="todos.length === 0" class="empty-message">Geen taken</p>
    </div>
  </div>

  <div class="map">
    <div class="app">
      <h1>Maak een map</h1>

      <div class="input-section">
        <input 
          v-model="newMap" 
          @keyup.enter="addMap"
          placeholder="Naam van map..."
          class="todo-input"
        />
        <button @click="addMap" class="add-button">Toevoegen</button>
      </div>

      <ul class="todo-list maps-grid">
        <li 
          v-for="map in maps"
          :key="map.id"
          class="todo-item"
          :class="{ completed: map.completed }"
          @dragover.prevent
          @drop="dropTodo(map)"
        >
          <!-- Map naam + voltooid checkbox + delete -->
          <input
            type="checkbox"
            :checked="map.completed"
            @change="toggleMap(map.id)"
            class="todo-checkbox"
          />
          <span class="todo-text">{{ map.text }}</span>
          <button @click="removeMap(map.id)" class="delete-button">x</button>

          <!-- TODOS BINNEN DE MAP TONEN -->
          <ul class="map-todos">
            <li 
              v-for="todo in map.todos" 
              :key="todo.id" 
              class="todo-item small"
              draggable="true"
              @dragstart="startDrag(todo, $event, map.id)"
            >
              <input
                type="checkbox"
                :checked="todo.completed"
                @change="toggleTodoInMap(map, todo)"
                class="todo-checkbox"
              />
              <span class="todo-text">{{ todo.text }}</span>
            </li>
          </ul>
        </li>
      </ul>

      <p v-if="maps.length === 0" class="empty-message">Geen mappen</p>
    </div>
  </div>
</div>
</template>

<style>

@media (max-width: 900px) {
  .layout {
    grid-template-columns: 1fr; /* 1 kolom op kleinere schermen */
  }
}

@media (max-width: 500px) {
  .todo-input, .add-button {  /* voor kleine scherm */
    width: 100%;
  }
}

@media (max-width: 600px) {
  .maps-grid {
    grid-template-columns: 1fr;
  }
}


body {
  margin: 0;
  background: linear-gradient(#dafbbf, #a6f7c2, #a6f7f2);
  font-family: Arial, sans-serif;
}

.layout {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  padding: 40px;
  height: 100vh;
}

.todo-container, .map {
  overflow-y: auto;
  padding: 40px;
  box-sizing: border-box;
}

.app {
  width: 100%;
  padding: 30px;
  background-color: #f0faee;
  border-radius: 8px;
}

h1 {
  text-align: center;
  margin-bottom: 30px;
  font-weight: 400;
  font-size: 36px;
}

.input-section {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.todo-input {
  flex: 1 1 200px;
  padding: 14px 18px;
  font-size: 18px;
  border: 2px solid #8f908f;
  border-radius: 5px;
  outline: none;
  background-color: #fdffff;
}

.todo-input:focus {
  border-color: #5fc466;
}

.add-button {
  flex: 0 0 auto;
  padding: 14px 22px;
  font-size: 18px;
  background-color: #5bba58;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}
.add-button:hover { background-color: #30653b; }

.todo-list {
  list-style: none;
  padding: 0;
}

.todo-item {
  display: flex;
  align-items: center;
  padding: 18px;
  margin-bottom: 15px;
  background-color: #ffffff;
  border-radius: 6px;
  transition: background-color 0.2s;
  font-size: 18px;
}
.todo-item:hover { background-color: #ffffff; }

.todo-item.completed .todo-text { text-decoration: line-through; }

.todo-item.dragging {
  opacity: 1;            /* licht transparant */
  transform: none;           /* geen verschuiving */
  border: 2px dashed #cdd4cd; /* highlight border */
  background-color: #cdd4cd;
}

.todo-checkbox { margin-right: 12px; cursor: pointer; }
.todo-text { flex: 1; }

.delete-button {
  padding: 6px 12px;
  font-size: 20px;
  background-color: #4ba364;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}
.delete-button:hover { background-color: #2d5b2c; }

.empty-message { text-align: center; font-style: italic; margin-top: 30px; }

/* TODOS BINNEN MAPS */
.map-todos {
  list-style: none;
  padding-left: 20px;
  margin-top: 10px;
}

.map-todos .todo-item.small {
  background-color: #cdd4cd;
  border-left: 4px solid #d2d8d2;
  padding: 8px 12px;
  font-size: 16px;
  border-radius: 4px;
  margin-bottom: 6px;
}

.maps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* 3 kolommen */
  gap: 20px; /* ruimte tussen de maps */
  padding: 0;
  list-style: none;
}

.maps-grid .todo-item {
  margin: 0; /* geen extra margin onder de items */
}
</style>