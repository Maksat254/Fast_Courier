<template>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Панель отчетов</h1>
            <p class="text-muted">Анализ и статистика по ключевым показателям</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="bi bi-cart"></i> Статистика по заказам</h5>
                        <p class="card-text">Просмотрите и анализируйте статистику по заказам, включая завершенные и активные.</p>
                        <router-link :to="{ name: 'orders_page_url' }" class="btn btn-outline-primary">Перейти</router-link>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="bi bi-bicycle"></i> Статистика по курьерам</h5>
                        <p class="card-text">Получите информацию о производительности курьеров и их маршрутах.</p>
                        <router-link :to="{ name: 'couriers_page_url' }" class="btn btn-outline-success">Перейти</router-link>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info"><i class="bi bi-map"></i> Статистика по маршрутам</h5>
                        <p class="card-text">Анализ эффективности маршрутов доставки и оптимизация процессов.</p>
                        <router-link :to="{ name: 'routes_page_url' }" class="btn btn-outline-info">Перейти</router-link>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body">
                        <h5 class="card-title text-warning"><i class="bi bi-cash-coin"></i> Финансовые показатели</h5>
                        <p class="card-text">Отслеживайте доходы, расходы и другие финансовые метрики.</p>
                        <router-link :to="{ name: 'financial_page_url' }" class="btn btn-outline-warning">Перейти</router-link>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body">
                        <h5 class="card-title text-danger"><i class="bi bi-chat-dots"></i> Отзывы и предложения</h5>
                        <p class="card-text">Просматривайте отзывы клиентов и внедряйте их предложения.</p>
                        <router-link :to="{ name: 'feedback_page_url' }" class="btn btn-outline-danger">Перейти</router-link>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body">
                        <h5 class="card-title text-danger"><i class="bi bi-chat-dots"></i>График курьеров</h5>
                        <p class="card-text">Просматривайте график курьеров</p>
                        <router-link :to="{ name: 'CourierSchedule_page_url' }" class="btn btn-outline-danger">Перейти</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <h1>Добро пожаловать в систему</h1>
            <p>Для продолжения выберите ваш вход:</p>
            <div>
                <router-link to="/courier/login" class="btn btn-primary me-2">Вход для курьеров</router-link>
<!--                <router-link to="/admin" class="btn btn-secondary">Вход для админов</router-link>-->
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const couriers = ref([]);
const clients = ref([]);
const restaurants = ref([]);
const statistics = ref({});

onMounted(async () => {
    try {
        const response = await axios.get('/api/admin-reports');
        const data = response.data;

        couriers.value = data.couriers;
        clients.value = data.clients;
        restaurants.value = data.restaurants;
        statistics.value = data.statistics;
    } catch (error) {
        console.error("Ошибка при получении данных:", error);
    }
});
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: scale(1.05);
}
</style>
