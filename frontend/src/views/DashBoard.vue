<template>
  <div class="container-fluid dashboard">
    <div class="categories-wrapper">
      <div ref="categoryContainer" class="categories d-flex">
        <div v-for="category in categories" :key="category.id" class="category-card">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5>{{ category.name }}</h5>
              <div>
                <button class="btn btn-sm btn-warning me-2" @click="openUpdateCategoryModal(category)">✎</button>
                <button class="btn btn-sm btn-danger" @click="deleteCategory(category.id)">✕</button>
              </div>
            </div>
            <div class="card-body">
              <ul class="task-list" :id="'category-' + category.id">
                <li :id="'task-' + task.id" :data-id="task.id" v-for="task in tasks.filter(task => task.category_id === category.id)" :key="task.id" class="task-item d-flex justify-content-between align-items-center">
                  <div @click="openUpdateTaskModal(task)" class="w-100" role="button">
                    <h5>{{ task.title }}</h5>
                    <p>{{ task.description }}</p>
                  </div>
                  <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)">✕</button>
                </li>
              </ul>
              <button class="btn btn-primary w-100 mt-2" @click="openTaskModal(category.id)">+ New Task</button>
            </div>
          </div>
        </div>
        
        <div class="add-category-card">
          <button class="btn btn-success" @click="openCategoryModal">+ Add Category</button>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <div v-if="showCategoryModal" class="modal-backdrop">
      <div class="modal-dialog">
        <div class="modal-content">
          <h4 class="text-white">Add New Category</h4>
          <input v-model="newCategory.name" class="form-control" placeholder="Category Name" />
          <button class="btn btn-success mt-3" @click="createCategory">Save</button>
          <button class="btn btn-secondary mt-2" @click="closeModals">Cancel</button>
        </div>
      </div>
    </div>

    <div v-if="showUpdateCategoryModal" class="modal-backdrop">
      <div class="modal-dialog">
        <div class="modal-content">
          <h4 class="text-white">Update Category</h4>
          <input v-model="updateCategory.name" class="form-control" placeholder="Category Name" />
          <button class="btn btn-success mt-3" @click="editCategory">Save</button>
          <button class="btn btn-secondary mt-2" @click="closeModals">Cancel</button>
        </div>
      </div>
    </div>
    
    <div v-if="showTaskModal" class="modal-backdrop">
      <div class="modal-dialog">
        <div class="modal-content">
          <h4 class="text-white">Add New Task</h4>
          <input v-model="newTask.title" class="form-control" placeholder="Task Title" /><br />
          <input v-model="newTask.description" class="form-control" placeholder="Task Description" />
          <button class="btn btn-success mt-3" @click="createTask">Save</button>
          <button class="btn btn-secondary mt-2" @click="closeModals">Cancel</button>
        </div>
      </div>
    </div>

    <div v-if="showUpdateTaskModal" class="modal-backdrop">
      <div class="modal-dialog">
        <div class="modal-content">
          <h4 class="text-white">Update Task</h4>
          <input v-model="updateTask.title" class="form-control" placeholder="Task Title" /><br />
          <input v-model="updateTask.description" class="form-control" placeholder="Task Description" />
          <button class="btn btn-success mt-3" @click="editTask">Save</button>
          <button class="btn btn-secondary mt-2" @click="closeModals">Cancel</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { API_BASE_URL } from "../config";
import Sortable from "sortablejs";

export default {
  data() {
    return {
      categories: [],
      tasks: [],
      showCategoryModal: false,
      showUpdateCategoryModal: false,
      showTaskModal: false,
      showUpdateTaskModal: false,
      newCategory: { name: "" },
      updateCategory: { id: null, name: "" },
      newTask: { title: "", description: "", category_id: null },
      updateTask: { id: null, title: "", description: "", category_id: null },
    };
  },
  mounted() {
    this.fetchCategories();
    this.fetchTasks();
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await fetch(`${API_BASE_URL}/category`, {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });

        if (!response.ok) throw new Error("Failed to fetch categories");
        const data = await response.json();
        this.categories = data;
      } catch (error) {
        console.error(error.message);
      }
    },

    async fetchTasks() {
      try {
        const response = await fetch(`${API_BASE_URL}/task`, {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });

        if (!response.ok) throw new Error("Failed to fetch tasks");
        const data = await response.json();
        this.tasks = data;

        this.$nextTick(() => {
          this.enableDragAndDrop();
        });

      } catch (error) {
        console.error(error.message);
      }
    },

    closeModals() {
      this.showTaskModal = false;
      this.showUpdateTaskModal = false;
      this.showCategoryModal = false;
      this.showUpdateCategoryModal = false;
      this.newCategory.name = "";
      this.newTask.title = "";
      this.newTask.description = "";
    },

    openCategoryModal() {
      this.showCategoryModal = true;
    },

    openUpdateCategoryModal(category) {
      this.updateCategory = { ...category };
      this.showUpdateCategoryModal = true;
    },

    openTaskModal(category_id) {
      this.newTask = { category_id };
      this.showTaskModal = true;
    },

    openUpdateTaskModal(task) {
      this.updateTask = { ...task };
      this.showUpdateTaskModal = true;
    },

    async createCategory() {
      try {
        const response = await fetch(`${API_BASE_URL}/category`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(this.newCategory),
        });

        if (!response.ok) throw new Error("Error creating category");
        this.fetchCategories();
        this.closeModals();
      } catch (error) {
        console.error(error.message);
      }
    },

    async editCategory() {
      try {
        const response = await fetch(`${API_BASE_URL}/category/${this.updateCategory.id}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify({ name: this.updateCategory.name }),
        });

        if (!response.ok) throw new Error("Error updating category");
        this.fetchCategories();
        this.closeModals();
      } catch (error) {
        console.error(error.message);
      }
    },

    async deleteCategory(id) {
      if (!confirm("Are you sure?")) return;
      try {
        await fetch(`${API_BASE_URL}/category/${id}`, {
          method: "DELETE",
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });

        this.fetchCategories();
      } catch (error) {
        console.error(error.message);
      }
    },

    async createTask() {
      try {
        const response = await fetch(`${API_BASE_URL}/task`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(this.newTask),
        });
        console.log(JSON.stringify(this.newTask))
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Error creating task");
        }

        this.fetchTasks();
        this.closeModals();
      } catch (error) {
        console.error("Error creating task:", error.message);
        alert("Failed to create task: " + error.message);
      }
    },

    async editTask() {
      try {
        const response = await fetch(`${API_BASE_URL}/task/${this.updateTask.id}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify({ title: this.updateTask.title, description: this.updateTask.description }),
        });

        if (!response.ok) throw new Error("Error updating task");
        this.fetchTasks();
        this.closeModals();
      } catch (error) {
        console.error(error.message);
      }
    },

    async deleteTask(id) {
      if (!confirm("Are you sure?")) return;
      try {
        await fetch(`${API_BASE_URL}/task/${id}`, {
          method: "DELETE",
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });

        this.fetchTasks();
      } catch (error) {
        console.error(error.message);
      }
    },

    //Drag and drop function to update task's category
    enableDragAndDrop() {
      this.categories.forEach((category) => {
        const el = document.getElementById(`category-${category.id}`);
        if (el) {
          new Sortable(el, {
            group: "tasks",
            animation: 150,
            onEnd: (evt) => {
              const taskId = evt.item.dataset.id;
              const newCategoryId = evt.to.id.replace("category-", "");
              this.moveTask(taskId, newCategoryId);
            },
          });
        }
      });
    },

    async moveTask(taskId, newCategoryId) {
      try {
        await fetch(`${API_BASE_URL}/task/${taskId}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify({ category_id: newCategoryId }),
        });

        this.fetchCategories();
      } catch (error) {
        console.error(error.message);
      }
    },
  },
};
</script>