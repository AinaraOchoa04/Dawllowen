# 🎃 DAWlloween - Proyecto Transversal DAW

¡Bienvenido/a a **DAWlloween**! Este repositorio contiene la aplicación web temática desarrollada para el proyecto transversal del Grado Superior de Desarrollo de Aplicaciones Web (DAW). Integra los conocimientos y requerimientos técnicos de cuatro módulos principales: **Diseño de Interfaces Web (DIW)**, **Despliegue de Aplicaciones Web (DAW)**, **Desarrollo Web en Entorno Cliente (DWEC)** y **Desarrollo Web en Entorno Servidor (DWES)**[cite: 3].

---

## 📋 Índice

1. [Descripción General](#-descripción-general)
2. [Estructura del Proyecto](#-estructura-del-proyecto)
3. [🎨 Diseño de Interfaces Web (DIW)](#-diseño-de-interfaces-web-diw)
4. [🛠️ Despliegue de Aplicaciones Web (DAW)](#-despliegue-de-aplicaciones-web-daw)
5. [⚡ Entorno Cliente (DWEC)](#-entorno-cliente-dwec)
6. [🐘 Entorno Servidor (DWES)](#-entorno-servidor-dwes)
7. [📖 Guía de Usuario (Manual Accesible)](#-guía-de-usuario-manual-accesible)
8. [🚀 Instalación y Puesta en Marcha](#-instalación-y-puesta-en-marcha)
9. [✒️ Autores](#-autores)

---

## 🎃 Descripción General

**DAWlloween** es un portal web inmersivo centrado en la festividad de Halloween. Combina un diseño temático oscuro y atractivo, un juego interactivo en JavaScript, dinamismo en pantalla y un backend en PHP que gestiona usuarios mediante sesiones y permite el envío de correos directos a "Jack Skellington".

---

## 📁 Estructura del Proyecto

```text
Dawllowen/
└── dawlloween/
    └── public/
        ├── index.html        # Página principal y portada temática
        ├── aboutus.html      # Información del equipo de desarrollo
        ├── recetas.html      # Catálogo de recetas terroríficas en cards
        ├── juego.html        # Minijuego desarrollado en JS
        ├── css/              # Hojas de estilo temáticas (CSS3 / Bootstrap)
        ├── js/               # Scripts interactivos y lógica del juego (DOM)
        ├── assets/           # Recursos multimedias (imágenes, iconos, audios)
        ├── VarGlobales.php   # Matriz de usuarios, $_SESSION y funciones globales
        ├── login.php         # Formulario e inicio de sesión de usuarios
        ├── altaUsuario.php   # Formulario de registro de nuevos usuarios
        ├── cambioPass.php    # Formulario para restablecer contraseña
        └── correo.php        # Formulario de envío de pesadillas a Jack (mail PHP)

Diseño de Interfaces Web (DIW)El diseño de la aplicación cumple con los principios de Interacción Persona-Ordenador (IPO), buscando ser accesible, intuitivo y sin sobrecargar la memoria del usuario.   Sitemap & Labelling: Arquitectura sencilla orientada a una navegación clara a través de la barra superior de menú.   Paleta de Colores: Basada en tonos oscuros de terror con altos contrastes (morados oscuros #1a0933, verdes tóxicos #22c55e, tonos naranja calabaza y negros de fondo).   Tipografía: Uso de Google Fonts con tipografías temáticas estilizadas para los encabezados (ej. Creepster) y tipografías limpias y sans-serif para la lectura fluida de los bloques de texto.   Componentes y Recetas: La sección recetas.html implementa un catálogo basado en cards interactivas de Bootstrap. Las recetas destacadas cuentan con distinciones visuales (marcos dorados e iconografía especial).

Despliegue de Aplicaciones Web (DAW)El sitio web está preparado para servirse desde un entorno de producción o pruebas bajo el servidor Apache (XAMPP / Linux Ubuntu / Kali).   Página "Próximamente": Despliegue inicial con la pantalla de mantenimiento temática "Despertando al monstruo...".   Virtual Host: Configuración de un servidor virtual en Apache apuntando a la carpeta /public.   Resolución Local DNS: Modificación del archivo de hosts local (/etc/hosts en Linux o C:\Windows\System32\drivers\etc\hosts en Windows) para redirigir las peticiones del dominio ficticio halloween.dev a la IP 127.0.0.1:   Plaintext127.0.0.1   halloween.dev
Seguridad y Permisos: Ajuste de permisos en el sistema de archivos (chmod) para asegurar que solo los recursos estáticos públicos de la carpeta sean accesibles por el servidor.

⚡ Entorno Cliente (DWEC)Toda la interactividad en el navegador se ha desarrollado con JavaScript ES6+ vanilla, manipulando directamente el DOM sin copiar código de terceros ni herramientas generativas.   Manipulación del DOM: Dinamismo en vivo dentro de las páginas (efectos visuales al pasar el ratón por los botones, ventanas emergentes y transiciones).   Minijuego Temático: En la sección juego.html se integra un juego totalmente interactivo en tiempo real (lógica de estado, control de teclado/ratón, conteo de puntuación y condiciones de victoria/derrota).

🐘 Entorno Servidor (DWES)El backend de la aplicación se estructura en PHP estructurado, limpio y comentado, siguiendo el esquema recomendado:   Plaintext
               ┌──────────────────┐
               │ VarGlobales.php  │
               └────────┬─────────┘
                        │ (include)
     ┌──────────────┬───┴──────────┬──────────────┐
     ▼              ▼              ▼              ▼
┌──────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────┐
│login.php │ │altaUsuario.php││cambioPass.php│ │correo.php│
└──────────┘ └──────────────┘ └──────────────┘ └──────────┘
VarGlobales.php: Centraliza las variables de sesión (session_start()) y almacena la matriz bidimensional con los usuarios (Usuario, Contraseña, Email, Perfil, Pregunta, UsuarioActual). Contiene las funciones globales:   AltaUsuario(): Agrega nuevos usuarios a la matriz.   cambioContraseña(): Comprueba la respuesta de seguridad y actualiza la clave.   currentUser(): Guarda la información del usuario autenticado.   login.php: Gestión de acceso vía POST. Si los datos son correctos, inicia la sesión e integra redirección automática.   altaUsuario.php: Formulario de registro para agregar nuevos usuarios con rol predeterminado "user".   cambioPass.php: Formulario para recuperación y cambio de credenciales mediante pregunta de seguridad.   correo.php (Envío a Jack Skellington): Formulario para redactar un mensaje con pesadillas a Jack. Hace uso de la función mail() de PHP utilizando el correo del usuario logueado en la cabecera FROM, habiendo sido configurados previamente los archivos php.ini y sendmail.ini de XAMPP con el servidor SMTP.

📖 Guía de Usuario (Manual Accesible)Esta guía está pensada para que cualquier persona, sin importar sus conocimientos informáticos, pueda disfrutar de la web sin perderse.Navegar por la Web:En la parte superior de la pantalla verás un menú con varios botones para ir a las distintas zonas: Inicio, Sobre Nosotros, Recetas, Juego y Login/Registro.   Acceder o Registrarse:Haz clic en Login. Si no tienes cuenta, pulsa en el enlace "Registrar nuevo usuario", rellena tu nombre, correo y contraseña y pulsa en enviar.   Si se te olvida la contraseña, haz clic en "Cambiar contraseña", responde a tu pregunta secreta de seguridad y podrás poner una nueva.   Ver las Recetas de Halloween:En la sección Recetas, verás fichas con platos terroríficos. Las recetas marcadas con un borde dorado o una estrella son las más especiales ("Recetas Top").   Jugar al Minijuego:Entra en la pestaña Juego y lee las instrucciones en pantalla para empezar a jugar con tu ratón o teclado.Enviar un mensaje a Jack Skellington:Una vez iniciada la sesión, entra en la sección Correo a Jack. Escribe un asunto, redacta tu pesadilla en la caja de texto y pulsa el botón "Enviar". Tu mensaje volará directamente al correo de Jack.

🚀 Instalación y Puesta en MarchaRequisitos previosServidor local con PHP 8.x y Apache (XAMPP recomendable).   Editor de código (ej. VS Code).Pasos de configuraciónClonar el repositorio:Bashgit clone [https://github.com/AinaraOchoa04/Dawllowen.git](https://github.com/AinaraOchoa04/Dawllowen.git)
Copiar la carpeta:
Mueve el contenido de la carpeta dawlloween/public al directorio de documentos de tu servidor web (ej. C:\xampp\htdocs\dawlloween o /var/www/html/dawlloween).   Configurar el servidor de correo (XAMPP):Modifica php.ini y sendmail.ini introduciendo la credencial SMTP (ejemplo con Yahoo) para habilitar la función mail().   Abrir en el navegador:
Accede a http://localhost/dawlloween o configura el Virtual Host local http://halloween.dev.   ✒️ AutoresProyecto realizado en grupo para el ciclo formativo de grado superior DAW
