<template>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="width: 400px;">
            <h3 class="text-center mb-4">Вход</h3>
            <form @submit.prevent="handleLogin">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        class="form-control"
                        v-model="email"
                        placeholder="Введите ваш email"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        v-model="password"
                        placeholder="Введите ваш пароль"
                        required
                    />
                </div>
                <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
                    {{ isLoading ? 'Вход...' : 'Войти' }}
                </button>
                <div v-if="errorMessage" class="mt-3 text-danger text-center">
                    {{ errorMessage }}
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const router = useRouter();

const handleLogin = async () => {
    isLoading.value = true;
    errorMessage.value = '';

    const SUPER_ADMIN = {
        email: "superadmin@example.com",
        password: "123456789",
    };
    try {
        if (email.value === SUPER_ADMIN.email && password.value === SUPER_ADMIN.password) {
            localStorage.setItem('authToken', 'super_admin_token');
            localStorage.setItem('userRole', 'admin');
            router.push('/admin/reports');
        } else {
            throw new Error("Неверный email или пароль");
        }
    } catch (error) {
        errorMessage.value = error.message || 'Ошибка входа. Проверьте данные.';
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>

</style>
