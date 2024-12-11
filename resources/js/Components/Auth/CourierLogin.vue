<template>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card p-4 shadow" style="width: 400px;">
            <h2 class="text-center mb-4">Вход для курьеров</h2>
            <form @submit.prevent="login">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input v-model="credentials.email" type="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input v-model="credentials.password" type="password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Войти</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            credentials: { email: "", password: "" },
        };
    },
    methods: {
        login() {
            axios.post("/api/courier/login", this.credentials).then((response) => {
                localStorage.setItem("courierToken", response.data.token);
                this.$router.push("/courier/dashboard");
            });
        },
    },
};
</script>
