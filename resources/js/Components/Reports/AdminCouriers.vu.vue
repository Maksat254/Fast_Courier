<template>
    <div class="container my-5">
        <h1 class="mb-4">Управление курьерами</h1>

        <div class="card mb-4">
            <div class="card-header">Добавить курьера</div>
            <div class="card-body">
                <form @submit.prevent="addCourier">
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input v-model="newCourier.name" type="text" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input v-model="newCourier.email" type="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input v-model="newCourier.password" type="password" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>

        <!-- Таблица курьеров -->
        <div class="card">
            <div class="card-header">Список курьеров</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="courier in couriers" :key="courier.id">
                        <td>{{ courier.id }}</td>
                        <td>{{ courier.name }}</td>
                        <td>{{ courier.email }}</td>
                        <td>
                            <button @click="deleteCourier(courier.id)" class="btn btn-danger btn-sm">
                                Удалить
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            couriers: [],
            newCourier: { name: "", email: "", password: "" },
        };
    },
    methods: {
        fetchCouriers() {
            axios.get("/api/admin/couriers").then((response) => {
                this.couriers = response.data;
            });
        },
        addCourier() {
            axios.post("/api/admin/couriers", this.newCourier).then(() => {
                this.newCourier = { name: "", email: "", password: "" };
                this.fetchCouriers();
            });
        },
        deleteCourier(id) {
            axios.delete(`/api/admin/couriers/${id}`).then(() => {
                this.fetchCouriers();
            });
        },
    },
    mounted() {
        this.fetchCouriers();
    },
};
</script>
