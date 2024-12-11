<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Финансовая и общая статистика</h1>

        <form @submit.prevent="fetchReports" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="startDate" class="form-label">Начало:</label>
                <input type="date" id="startDate" v-model="startDate" class="form-control" required />
            </div>
            <div class="col-md-4">
                <label for="endDate" class="form-label">Конец:</label>
                <input type="date" id="endDate" v-model="endDate" class="form-control" required />
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Сгенерировать отчет</button>
            </div>
        </form>

        <div v-if="financialData" class="mb-5">
            <h2>Финансовая статистика</h2>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Доходы (Выручка): <span class="text-success">{{ financialData.revenue }} ₽</span></h5>
                </div>
                <div class="col-md-6">
                    <h5>Расходы (Курьеры): <span class="text-danger">{{ financialData.expenses }} ₽</span></h5>
                </div>
            </div>
            <div>
                <h5>Итоговая прибыль: <span class="text-primary">{{ financialData.profit }} ₽</span></h5>
            </div>
            <h5 class="mt-3">История выплат курьерам:</h5>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID Курьера</th>
                    <th>Имя</th>
                    <th>Выплаты</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="courier in financialData.couriers" :key="courier.id">
                    <td>{{ courier.id }}</td>
                    <td>{{ courier.name }}</td>
                    <td>{{ courier.payout }} ₽</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-if="generalData">
            <h2>Общая статистика</h2>
            <div class="row mb-3">
                <div class="col-md-4">
                    <h5>Количество выполненных заказов: {{ generalData.completedOrders }}</h5>
                </div>
                <div class="col-md-4">
                    <h5>Среднее время доставки: {{ generalData.avgDeliveryTime }} мин.</h5>
                </div>
                <div class="col-md-4">
                    <h5>Активность курьеров: {{ generalData.activeCouriers }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Загруженность курьеров:</h5>
                    <ul>
                        <li v-for="courier in generalData.courierLoad" :key="courier.id">
                            {{ courier.name }}: {{ courier.orders }} заказов
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Популярность услуг:</h5>
                    <ul>
                        <li v-for="service in generalData.servicePopularity" :key="service.id">
                            {{ service.name }}: {{ service.usage }} раз
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button @click="downloadReport('pdf')" class="btn btn-secondary me-2">Скачать PDF</button>
            <button @click="downloadReport('csv')" class="btn btn-secondary">Скачать CSV</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const startDate = ref("");
const endDate = ref("");
const financialData = ref(null);
const generalData = ref(null);

const fetchReports = async () => {
    try {
        const response = await fetch(
            `/api/reports?start_date=${startDate.value}&end_date=${endDate.value}`
        );
        const data = await response.json();

        financialData.value = data.financial || null;
        generalData.value = data.general || null;
    } catch (error) {
        alert("Ошибка загрузки данных. Попробуйте позже.");
    }
};

const downloadReport = (type) => {
    const url = `/api/reports/download?start_date=${startDate.value}&end_date=${endDate.value}&type=${type}`;
    window.open(url, "_blank");
};
</script>

<style>
.table th, .table td {
    text-align: center;
    vertical-align: middle;
}
</style>
