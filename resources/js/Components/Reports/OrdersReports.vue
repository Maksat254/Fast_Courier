<template>
    <div class="container mt-5">
        <h1 class="mb-4">Отчет по заказам</h1>

            <form @submit.prevent="fetchReport" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <label for="startDate" class="form-label">Начальная дата:</label>
                    <input
                        type="date"
                        id="startDate"
                        v-model="startDate"
                        class="form-control"
                        required
                    />
                </div>
                <div class="col-md-5">
                    <label for="endDate" class="form-label">Конечная дата:</label>
                    <input
                        type="date"
                        id="endDate"
                        v-model="endDate"
                        class="form-control"
                        required
                    />
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Сгенерировать отчет
                    </button>
                </div>
            </div>
        </form>

        <div v-if="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
        </div>

        <div v-else>
            <div v-if="rows.length > 0">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th v-for="column in columns" :key="column.key">{{ column.label }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in rows" :key="row.order_id">
                        <td>{{ row.order_id }}</td>
                        <td>{{ row.clients_name }}</td>
                        <td>{{ row.status }}</td>
                        <td>{{ row.created_at }}</td>
                        <td>{{ row.completed_at }}</td>
                        <td>{{ row.total.amount }}</td>
                        <td>{{ row.product }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between">
                    <button
                        class="btn btn-secondary"
                        :disabled="pagination.current_page === 1"
                        @click="changePage(pagination.current_page - 1)"
                    >
                        Назад
                    </button>
                    <span>Страница {{ pagination.current_page }} из {{ pagination.total_pages }}</span>
                    <button
                        class="btn btn-secondary"
                        :disabled="pagination.current_page === pagination.total_pages"
                        @click="changePage(pagination.current_page + 1)"
                    >
                        Вперед
                    </button>
                </div>
            </div>
            <div v-else class="alert alert-dark" role="alert">
                Укажите дату для получения историю заказа
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const startDate = ref('');
const endDate = ref('');
const loading = ref(false);
const columns = ref([]);
const rows = ref([]);
const pagination = ref({
    current_page: 1,
    total_pages: 1,
    per_page: 10,
    total: 0,
});

const fetchReport = async () => {
    loading.value = true;

    try {
        const response = await fetch(`/api/orders/reports/orders?start_date=${startDate.value}&end_date=${endDate.value}&page=${pagination.value.current_page}`);
        const data = await response.json();

        rows.value = data.data;
        columns.value = data.columns;
        pagination.value = data.pagination;
    } catch (error) {
        console.error('Ошибка при загрузке заказов:', error);
    } finally {
        loading.value = false;
    }
};

const changePage = (page) => {
    if (page >= 1 && page <= pagination.value.total_pages) {
        pagination.value.current_page = page;
        fetchReport();
    }
};
</script>

<style scoped>
.table {
    border: 1px solid #ddd;
    margin-top: 20px;
}
.table th, .table td {
    text-align: center;
}
</style>
