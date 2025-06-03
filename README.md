# 📦 Sistema de Encomiendas - Leandro

Este proyecto es un sistema web de gestión de encomiendas desarrollado con **PHP** bajo el enfoque de **Clean Architecture**. Utiliza PostgreSQL como base de datos y está diseñado para facilitar la gestión de paquetes, usuarios, facturación y seguimiento de envíos.

## 🚀 Tecnologías utilizadas

- PHP 8.x
- PostgreSQL
- JavaScript (Tailwind CSS, librerías complementarias)
- EmailJS (para el envío de correos)
- Clean Architecture (Dominio, Infraestructura, Presentación)

## 📁 Estructura del Proyecto

```bash
app/
├── domain/         # Entidades y lógica de negocio
├── infrastructure/ # Repositorios, servicios externos (email, DB)
├── presentation/   # Controladores y vistas
public/             # Archivos públicos (CSS, JS, imágenes)
├── services/       # Servicios de negocio
config/             # Configuración de la aplicación (enrutador)
vendor/             # Dependencias de Composer