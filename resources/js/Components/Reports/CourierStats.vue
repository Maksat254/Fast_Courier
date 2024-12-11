<template>
    <div class="container mt-4">
        <h1 class="mb-4">Отчет по курьерам</h1>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Общая статистика</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Количество активных курьеров:</strong> {{ activeCouriers.length }}
                            </li>
                            <li class="list-group-item">
                                <strong>Среднее количество заказов на курьера:</strong> {{ stats.orders_per_courier }}
                            </li>
                            <li class="list-group-item">
                                <strong>Среднее количество заказов в день:</strong> {{ stats.average_orders_per_day }}
                            </li>
                            <li class="list-group-item">
                                <strong>Среднее время доставки:</strong> {{ stats.average_delivery_time }} минут
                            </li>
                            <li class="list-group-item">
                                <strong>Процент успешных доставок:</strong> {{ stats.success_rate }}%
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button @click="fetchCourierStats" class="btn btn-primary">Обновить данные</button>
            <button @click="toggleActiveCouriers" class="btn btn-secondary">
                {{ showActiveCouriers ? 'Скрыть' : 'Показать' }} активных курьеров
            </button>
            <button @click="toggleInactiveCouriers" class="btn btn-secondary">
                {{ showInactiveCouriers ? 'Скрыть' : 'Показать' }} неактивных курьеров
            </button>
        </div>

        <div v-if="showActiveCouriers" class="mt-4">
            <h2>Активные курьеры</h2>
            <ul class="list-group">
                <li
                    v-for="courier in activeCouriers"
                    :key="courier.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ courier.name }}</span>
                    <span class="badge bg-success">{{ courier.is_active ? 'Активен' : 'Неактивен' }}</span>
                </li>
            </ul>
        </div>

        <div v-if="showInactiveCouriers" class="mt-4">
            <h2>Неактивные курьеры</h2>
            <ul class="list-group">
                <li
                    v-for="courier in inactiveCouriers"
                    :key="courier.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ courier.name }}</span>
                    <span class="badge bg-danger">{{ courier.is_active ? 'Активен' : 'Неактивен' }}</span>
                </li>
            </ul>
        </div>

        <router-link to="/admin/reports" class="btn btn-light mt-4">Назад к отчетам</router-link>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref({
    orders_per_courier: 0,
    average_orders_per_day: 0,
    average_delivery_time: 0,
    success_rate: 0,
});

const activeCouriers = ref([]);
const inactiveCouriers = ref([]);

const showActiveCouriers = ref(false);
const showInactiveCouriers = ref(false);

const fetchCourierStats = async () => {
    try {
        const response = await axios.get('/api/admin/reports/courier-stats');
        stats.value = response.data;
    } catch (error) {
        alert('Ошибка при загрузке статистики. Попробуйте позже.');
        console.error("Ошибка статистики:", error);
    }
};

const fetchActiveCouriers = async () => {
    try {
        const response = await axios.get('/api/couriers/active');
        activeCouriers.value = response.data;
    } catch (error) {
        alert('Ошибка при загрузке списка активных курьеров.');
        console.error("Ошибка курьеров:", error);
    }
};

const fetchInactiveCouriers = async () => {
    try {
        const response = await axios.get('/api/couriers/inactive');
        inactiveCouriers.value = response.data;
    } catch (error) {
        alert('Ошибка при загрузке списка неактивных курьеров.');
        console.error("Ошибка курьеров:", error);
    }
};

const toggleActiveCouriers = async () => {
    if (!showActiveCouriers.value) {
        await fetchActiveCouriers();
    }
    showActiveCouriers.value = !showActiveCouriers.value;
};

const toggleInactiveCouriers = async () => {
    if (!showInactiveCouriers.value) {
        await fetchInactiveCouriers();
    }
    showInactiveCouriers.value = !showInactiveCouriers.value;
};

onMounted(fetchCourierStats);
</script>

<style>
.container {
    max-width: 800px;
}
</style>
