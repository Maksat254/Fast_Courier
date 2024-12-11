<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const couriers = ref([]);

const fetchCouriers = async () => {
    try {
        const response = await axios.get('/api/admin/couriers');
        couriers.value = response.data;
    } catch (error) {
        console.error("Ошибка загрузки курьеров:", error);
    }
};

const pauseCourier = async (id) => {
    try {
        await axios.post(`/api/admin/couriers/${id}/pause`);
        alert("Пауза установлена на 30 минут");
        fetchCouriers();
    } catch (error) {
        console.error("Ошибка установки паузы:", error);
    }
};

const endDayCourier = async (id) => {
    try {
        await axios.post(`/api/admin/couriers/${id}/end-day`);
        alert("Рабочий день завершен");
        fetchCouriers();
    } catch (error) {
        console.error("Ошибка завершения дня:", error);
    }
};


onMounted(() => {
    fetchCouriers();
});
</script>

<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Управление графиками курьеров</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Статус</th>
                    <th>Последняя активность</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="courier in couriers" :key="courier.id">
                    <td>{{ courier.id }}</td>
                    <td>{{ courier.name }}</td>
                    <td>{{ courier.status }}</td>
                    <td>{{ courier.last_active }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm me-2" @click="pauseCourier(courier.id)">
                            Пауза
                        </button>
                        <button class="btn btn-danger btn-sm me-2" @click="endDayCourier(courier.id)">
                            Завершить день
                        </button>
                    </td>
                </tr>
                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: !couriers.prev_page_url }">
                            <button class="page-link" @click="fetchCouriers(couriers.prev_page_url)">Назад</button>
                        </li>
                        <li class="page-item" :class="{ disabled: !couriers.next_page_url }">
                            <button class="page-link" @click="fetchCouriers(couriers.next_page_url)">Вперед</button>
                        </li>
                    </ul>
                </nav>

                </tbody>
            </table>
        </div>
    </div>
</template>


<style scoped>

</style>
