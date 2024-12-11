<template>
    <div class="container my-5">
        <h1 class="mb-4">Личный кабинет</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">Информация</div>
                    <div class="card-body">
                        <p><strong>Имя:</strong> {{ courier.name }}</p>
                        <p><strong>Email:</strong> {{ courier.email }}</p>
                        <p><strong>Общий заработок:</strong> {{ earnings }}₽</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">История заказов</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Описание</th>
                                <th>Сумма</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders" :key="order.id">
                                <td>{{ order.id }}</td>
                                <td>{{ order.description }}</td>
                                <td>{{ order.amount }}₽</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            courier: {},
            orders: [],
            earnings: 0,
        };
    },
    methods: {
        fetchProfile() {
            axios
                .get("/api/courier/profile", {
                    headers: { Authorization: `Bearer ${localStorage.getItem("courierToken")}` },
                })
                .then((response) => {
                    this.courier = response.data;
                });
        },
        fetchOrders() {
            axios
                .get("/api/courier/orders", {
                    headers: { Authorization: `Bearer ${localStorage.getItem("courierToken")}` },
                })
                .then((response) => {
                    this.orders = response.data.orders;
                    this.earnings = response.data.earnings;
                });
        },
    },
    mounted() {
        this.fetchProfile();
        this.fetchOrders();
    },
};
</script>
