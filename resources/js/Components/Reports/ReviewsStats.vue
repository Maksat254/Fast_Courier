<template>
    <div>
        <h1>Отзывы и предложения</h1>
        <p>Общее количество отзывов: {{ data.total_feedback }}</p>
        <p>Средний рейтинг: {{ data.average_rating }}/5</p>
        <h3>Предложения:</h3>
        <ul>
            <li>Увеличить количество курьеров в пиковые часы</li>
        </ul>

        <router-link to="/admin/reports" class="btn btn-secondary mt-4">Назад к отчетам</router-link>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const data = ref({
    total_feedback: 300,
    average_rating: 4.8,
});
</script>
