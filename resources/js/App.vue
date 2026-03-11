<script setup>
// Importeer ref voor reactieve state
import { ref } from 'vue'

// Maak een reactieve variabele voor de nieuwe todo input
const newTodo = ref('')

// Maak een reactieve array voor alle todos
const todos = ref([])

// Maak een counter voor unieke todo IDs
const todoIdCounter = ref(0)

// Functie om een nieuwe todo toe te voegen
const addTodo = () => {
  // Check of de input niet leeg is
  if (newTodo.value.trim() !== '') {
    // Voeg nieuwe todo toe aan de array
    todos.value.push({
      id: todoIdCounter.value++, // Geef een unieke ID
      text: newTodo.value,        // Sla de todo tekst op
      completed: false            // Markeer als niet voltooid
    })
    // Reset het input veld
    newTodo.value = ''
  }
}

// Functie om een todo te verwijderen
const removeTodo = (id) => {
  // Filter de todo met het gegeven ID eruit
  todos.value = todos.value.filter(todo => todo.id !== id)
}

// Functie om de completed status van een todo te togglen
const toggleTodo = (id) => {
  // Zoek de todo en verander de completed status
  const todo = todos.value.find(todo => todo.id === id)
  if (todo) {
    todo.completed = !todo.completed
  }
}
</script>

<template>

<!-- Hoofd layout die todo en map naast elkaar zet -->
<div class="layout">

  <!-- Todo lijst (links) -->
  <div class="todo-container">

    <!-- Hoofd container van de todo app -->
    <div class="app">

      <!-- App titel -->
      <h1>To-Do List</h1>

      <!-- Input sectie voor nieuwe todos -->
      <div class="input-section">

        <!-- Input veld voor nieuwe todo -->
        <input 
          v-model="newTodo" 
          @keyup.enter="addTodo"
          placeholder="Voeg een taak toe..."
          class="todo-input"
        />

        <!-- Knop om een nieuwe todo toe te voegen -->
        <button @click="addTodo" class="add-button">
          Toevoegen
        </button>

      </div>

      <!-- Lijst met todos -->
      <ul class="todo-list">

        <!-- Loop door alle todos -->
        <li 
          v-for="todo in todos"
          :key="todo.id"
          class="todo-item"
          :class="{ completed: todo.completed }"
        >

          <!-- Checkbox om todo als voltooid te markeren -->
          <input 
            type="checkbox"
            :checked="todo.completed"
            @change="toggleTodo(todo.id)"
            class="todo-checkbox"
          />

          <!-- Tekst van de todo -->
          <span class="todo-text">{{ todo.text }}</span>

          <!-- Knop om een todo te verwijderen -->
          <button @click="removeTodo(todo.id)"
            class="delete-button">
          </button>

        </li>

      </ul>

      <!-- Bericht als er geen taken zijn -->
      <p v-if="todos.length === 0" class="empty-message">
        Geen taken
      </p>

    </div>

  </div>

  <!-- Map (rechts) -->
  <div class="map">
    <h1>Maak een map:</h1>
  </div>

  <div class="map container">
    
    <div/>

</div>

</template>
<style>

/* Algemene app styling */
body {
  margin: 0;
  background: linear-gradient(#dafbbf, #a6f7c2, #a6f7f2);
  font-family: Arial, sans-serif;
}

/* Main layout: todo links, map rechts */
.layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  height: 100vh;
}

/* Todo panel (links) */
.todo-container {
  overflow-y: auto;
  padding: 40px;
  box-sizing: border-box;
}

/* Map panel (rechts) */
.map {
  overflow-y: auto;
  padding: 40px;
  box-sizing: border-box;
  box-sizing: border-box;
}

/* Todo app container */
.app {
  width: 100%;
  margin: 0;
  padding: 30px;
  background-color: #f0faee;
  border-radius: 8px;
}

/* Titel styling */
h1 {
  text-align: center;
  color: #000000;
  margin-bottom: 30px;
  font-family: 'Google Sans', sans-serif;
  font-weight: 400;
  font-size: 36px;
}

/* Input sectie styling */
.input-section {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

/* Todo input veld */
.todo-input {
  flex: 1;
  padding: 14px 18px;
  font-size: 18px;
  border: 2px solid #8f908f;
  border-radius: 5px;
  outline: none;
  background-color: #fdffff;
  color: #000000;
}

/* Focus state input */
.todo-input:focus {
  border-color: #5fc466;
}

/* Toevoegen knop */
.add-button {
  padding: 14px 22px;
  font-size: 18px;
  background-color: #5bba58;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

/* Hover toevoegen knop */
.add-button:hover {
  background-color: #30653b;
}

/* Todo lijst */
.todo-list {
  list-style: none;
  padding: 0;
}

/* Todo item */
.todo-item {
  display: flex;
  align-items: center;
  padding: 18px;
  margin-bottom: 15px;
  background-color: #ffffff;
  border-radius: 6px;
  transition: background-color 0.2s;
  color: #000000;
  font-size: 18px;
}

/* Hover todo */
.todo-item:hover {
  background-color: #f5fff5;
}

/* Voltooide todo */
.todo-item.completed .todo-text {
  text-decoration: line-through;
  color: #000000;
}

/* Checkbox */
.todo-checkbox {
  margin-right: 12px;
  cursor: pointer;
}

/* Todo tekst */
.todo-text {
  flex: 1;
}

/* Verwijder knop */
.delete-button {
  padding: 6px 12px;
  font-size: 20px;
  background-color: #4ba364;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  transition: background-color 0.3s;
}

/* Hover verwijder knop */
.delete-button:hover {
  background-color: #2d5b2c;
}

/* Lege lijst bericht */
.empty-message {
  text-align: center;
  color: #000000;
  font-style: italic;
  margin-top: 30px;
}

</style>