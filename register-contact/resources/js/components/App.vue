<template>
  <Toaster position="top-center" richColors closeButton />
  <div class="flex justify-end mb-4 ">
    <div @click="showModal = true">
      <button class="button button1">Đăng ký</button>
    </div>
  </div>
  <table>
    <tr>
      <th class="px-4 py-3 border">No</th>
      <th class="px-4 py-3 border">Tên</th>
      <th class="px-4 py-3 border">Email</th>
      <th class="px-4 py-3 border">SĐT</th>
      <th class="px-4 py-3 border">Địa chỉ</th>

    </tr>
    <tbody>
      <tr v-for="(contact, idx) in contacts" :key="contact.id" class="bg-white border-b hover:bg-gray-50">
        <td>{{ contact.id }}</td>
        <td>{{ contact.name }}</td>
        <td>{{ contact.email }}</td>
        <td>{{ contact.phone }}</td>
        <td>{{ contact.address }}</td>

      </tr>
    </tbody>
  </table>

  <!-- <RegisterForm v-show="showModal" @close="showModal = false" /> -->
  <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
    <div class="modal">
      <div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
        <form @submit.prevent="submitForm">
          <label>Name</label>
          <input v-model="name" type="text" />
          <label>Email</label>
          <input v-model="email" type="text" />
          <label>Phone</label>
          <input v-model="phone" type="text" />
          <label>Address</label>
          <input v-model="address" type="text" />
          <input type="submit" value="Submit" />
        </form>
      </div>
    </div>
  </div>
</template>
<style>
table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td,
th {
  border: 1px solid #ddd;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

tr:hover {
  background-color: #ddd;
}

th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.button {
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.button1 {
  background-color: #04AA6D;
}

.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  background-color: #000000da;
}

.modal {
  text-align: center;
  background-color: white;
  height: 500px;
  width: 500px;
  margin-top: 10%;
  padding: 60px 0;
  border-radius: 20px;
}

.close {
  margin: 10% 0 0 16px;
  cursor: pointer;
}

.close-img {
  width: 25px;
}

.check {
  width: 150px;
}

h6 {
  font-weight: 500;
  font-size: 28px;
  margin: 20px 0;
}

p {
  font-size: 16px;
  margin: 20px 0;
}


form {
  border-radius: 5px;
  padding: 20px;
}

label {
  display: block;
  text-align: left;
}

input[type="text"],
select {
  width: 100%;
  padding: 12px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type="submit"] {
  width: 100%;
  background-color: #4caf50;
  color: white;
  padding: 14px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.sonner-toast-container {
  z-index: 9999 !important;
}
input[type="submit"]:hover {
  background-color: #45a049;
}
</style>
<script setup>
import { onMounted, ref } from 'vue';
import { toast,Toaster  } from "vue-sonner";

const contacts = ref([]);
const showModal = ref(false);
const name = ref('');
const email = ref('');
const phone = ref('');
const address = ref('');

async function submitForm() {
  try {
    const res = await fetch("http://127.0.0.1:8000/api/contact", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        phone: phone.value,
        address: address.value,
      }),
    });

    if (!res.ok) {

      const error = await res.json();
      if (error.errors) {
        const messages = Object.values(error.errors)
          .flat()
          .join("\n");
        toast.error(messages);
      }
      return;
    }

    toast.success("Đăng ký thành công!");
    showModal.value = false;
    await listData();
    name.value = "";
    email.value = "";
    phone.value = "";
    address.value = "";

  } catch (e) {
    console.log('a')
    toast.error("Có lỗi xảy ra!");
  }
}
onMounted(async () => {
  listData();
});
const listData = async () => {
  const res = await fetch('http://127.0.0.1:8000/api/contact');
  const data = await res.json();
  console.log("data", data);
  contacts.value = Array.isArray(data) ? data : [data];
}
</script>