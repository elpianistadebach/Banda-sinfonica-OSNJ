// archivo: auth.js

// 1. Importar el cliente de Supabase desde un CDN (Red de Entrega de Contenidos)
import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

// 2. Inicializar la conexión
// Reemplaza esto con los datos de tu panel (Project Settings -> API)
const supabaseUrl = 'TU_URL_DE_SUPABASE'
const supabaseKey = 'TU_CLAVE_ANONIMA_DE_SUPABASE'
const supabase = createClient(supabaseUrl, supabaseKey)

// 3. Manejo del formulario de Registro
const formRegistro = document.getElementById('formulario_registro');

if (formRegistro) {
    formRegistro.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        const nombre = document.getElementById('nombre_completo').value.trim();
        const email = document.getElementById('correo_electronico_reg').value.trim();
        const password = document.getElementById('contrasena_reg').value.trim();
        const confirmarPassword = document.getElementById('confirmar_contrasena').value.trim();

        if (!nombre || !email || !password || !confirmarPassword) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        if (password !== confirmarPassword) {
            alert("Las contraseñas no coinciden.");
            return;
        }

        const { data, error } = await supabase.auth.signUp({
            email: email,
            password: password,
            options: {
                data: {
                    nombre_completo: nombre 
                }
            }
        });

        if (error) {
            alert("Error al registrar: " + error.message); 
        } else {
            window.location.href = 'exito.html'; 
        }
    });
}

// 4. Manejo del formulario de Login
const formLogin = document.getElementById('formulario_login');

if (formLogin) {
    formLogin.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('correo_electronico_login').value.trim();
        const password = document.getElementById('contrasena_login').value.trim();

        if (!email || !password) {
            alert("Por favor, ingresa tu correo y contraseña.");
            return;
        }

        const { data, error } = await supabase.auth.signInWithPassword({
            email: email,
            password: password,
        });

        if (error) {
            alert("Error al iniciar sesión: " + error.message);
        } else {
            window.location.href = 'exito.html';
        }
    });
}
