<template>
    <div>
        <h1>Отчет по маршрутам</h1>
        <p>Средняя длина маршрута: {{ data.average_route_length }}</p>
        <p>Наиболее популярный маршрут: {{ data.most_popular }}</p>
        <p>Наименее популярный маршрут: {{ data.least_popular }}</p>

        <router-link to="/admin/reports" class="btn btn-secondary mt-4">Назад к отчетам</router-link>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const data = ref({
    average_route_length: "5 км",
    most_popular: "Центр -> Парк",
    least_popular: "Парк -> Округ",
});
</script>
