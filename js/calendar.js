document.addEventListener("DOMContentLoaded", function () {
  // Initialize Calendar
  const calendarGrid = document.querySelector(".calendar-grid");

  // Current date to highlight
  const today = new Date();
  const currentMonth = today.getMonth();
  const currentYear = today.getFullYear();

  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

  // Generate Calendar Days
  let calendarHTML = "";

  for (let i = 0; i < firstDayOfMonth; i++) {
    calendarHTML += `<div class="calendar-day"></div>`;
  }

  for (let i = 1; i <= daysInMonth; i++) {
    calendarHTML += `<div class="calendar-day" data-date="${i}">${i}</div>`;
  }

  calendarGrid.innerHTML = calendarHTML;

  // Select a date
  let selectedDate = null;
  const calendarDays = document.querySelectorAll(".calendar-day");

  calendarDays.forEach((day) => {
    day.addEventListener("click", function () {
      const selected = document.querySelector(".calendar-day.selected");
      if (selected) {
        selected.classList.remove("selected");
      }
      day.classList.add("selected");
      selectedDate = day.getAttribute("data-date");
      loadTasks(selectedDate);
    });
  });

  // Handle Task Form
  const taskForm = document.getElementById("taskForm");
  const taskTitleInput = document.getElementById("taskTitle");
  const taskDescriptionInput = document.getElementById("taskDescription");
  const taskDateInput = document.getElementById("taskDate");
  const taskTypeSelect = document.getElementById("taskType");

  taskForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (selectedDate) {
      const task = {
        title: taskTitleInput.value,
        description: taskDescriptionInput.value,
        date: selectedDate,
        type: taskTypeSelect.value,
      };

      saveTask(task);
    } else {
      alert("Please select a date to add the task.");
    }
  });

  // Store Tasks
  function saveTask(task) {
    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    tasks.push(task);
    localStorage.setItem("tasks", JSON.stringify(tasks));
    taskTitleInput.value = "";
    taskDescriptionInput.value = "";
    taskTypeSelect.value = "Normal";
    loadTasks(selectedDate);
  }

  // Load Tasks
  function loadTasks(date) {
    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    const tasksContainer = document.getElementById("tasksContainer");

    const filteredTasks = tasks.filter((task) => task.date === date);

    tasksContainer.innerHTML = "";
    filteredTasks.forEach((task) => {
      const taskElement = document.createElement("div");
      taskElement.classList.add("task");
      if (task.type === "Important") taskElement.classList.add("important");
      taskElement.innerHTML = `
        <h3>${task.title}</h3>
        <p>${task.description}</p>
        <span class="task-date">${task.date}</span>
        <button class="mark-complete" onclick="markTaskComplete(${tasks.indexOf(
          task
        )})">Mark as Complete</button>
      `;
      tasksContainer.appendChild(taskElement);
    });
  }

  // Mark Task Complete
  window.markTaskComplete = function (taskIndex) {
    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    const task = tasks[taskIndex];
    const completedTasks =
      JSON.parse(localStorage.getItem("completedTasks")) || [];
    completedTasks.push(task);
    localStorage.setItem("completedTasks", JSON.stringify(completedTasks));

    tasks.splice(taskIndex, 1);
    localStorage.setItem("tasks", JSON.stringify(tasks));

    loadTasks(selectedDate);
    loadCompletedTasks();
  };

  // Load Completed Tasks
  function loadCompletedTasks() {
    const completedTasks =
      JSON.parse(localStorage.getItem("completedTasks")) || [];
    const completedTasksContainer = document.getElementById("completedTasks");
    completedTasksContainer.innerHTML = "";

    completedTasks.forEach((task) => {
      const taskElement = document.createElement("li");
      taskElement.innerHTML = `
        <strong>${task.title}</strong><br />
        <em>${task.description}</em>
      `;
      completedTasksContainer.appendChild(taskElement);
    });
  }

  // Clear Completed Tasks
  document
    .getElementById("clearCompletedButton")
    .addEventListener("click", function () {
      localStorage.removeItem("completedTasks");
      loadCompletedTasks();
    });

  // Initialize Completed Tasks on Load
  loadCompletedTasks();
});
