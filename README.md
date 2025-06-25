# Katie Bray - Sistema Completo de Workshops y Membresías

## 📋 Descripción General

Este proyecto implementa un sistema completo de gestión de workshops y membresías premium para Katie Bray, desarrollado como tres plugins independientes de WordPress con integración Stripe y funcionalidades avanzadas.

## 🏗️ Arquitectura del Sistema

### Plugin A - KB Workshops
**Archivo principal:** `kb-workshops/kb-workshops.php`

**Funcionalidades:**
- Gestión de workshops (CPT)
- Sistema de reservas con Stripe
- Cálculo automático de descuentos para miembros premium
- Emails automáticos (confirmación y notificaciones admin)
- Panel de administración con reportes
- Integración con SMTP y Brevo

**Características técnicas:**
- Custom Post Type: `workshop`
- Custom Post Type: `workshop_booking`
- Tabla personalizada: `kb_workshop_bookings`
- AJAX para actualización de precios en tiempo real
- Shortcode: `[kb_workshop_booking_form]`

### Plugin B - KB Membership
**Archivo principal:** `kb-membership/kb-membership.php`

**Funcionalidades:**
- Suscripciones mensuales (€35/mes)
- Panel de usuario "Mi cuenta"
- Recursos premium exclusivos
- Sistema de mensajería interna
- Notificaciones en tiempo real
- Descuentos automáticos (25% por defecto)

**Características técnicas:**
- Custom Post Type: `premium_resource`
- Custom Post Type: `member_message`
- Tablas personalizadas: `kb_member_subscriptions`, `kb_member_messages`, `kb_member_notifications`
- Role personalizado: `premium_member`
- Shortcode: `[kb_member_dashboard]`

### Plugin C - KB Corporativo
**Archivo principal:** `kb-corporativo/kb-corporativo.php`

**Funcionalidades:**
- Formulario de leads corporativos
- Gestión de logos de empresas
- Bloque Gutenberg "Logo Wall"
- Notificaciones automáticas por email
- Panel de administración de leads

**Características técnicas:**
- Custom Post Type: `corporate_lead`
- Custom Post Type: `company_logo`
- Tabla personalizada: `kb_corporate_leads`
- Bloque Gutenberg personalizado
- Shortcode: `[kb_corporate_form]`

## 🚀 Instalación y Configuración

### Requisitos del Sistema
- WordPress 5.8+
- PHP 8.0+
- MySQL 5.7+ o MariaDB 10.3+
- SSL habilitado (requerido para Stripe)
- Memoria PHP: 256MB+

### Pasos de Instalación

1. **Subir plugins al servidor:**
   ```bash
   /wp-content/plugins/
   ├── kb-workshops/
   ├── kb-membership/
   └── kb-corporativo/
   ```

2. **Activar plugins en WordPress:**
   - Activar "KB Workshops"
   - Activar "KB Membership"
   - Activar "KB Corporativo"

3. **Configurar Stripe:**
   - Ir a Ajustes > KB Workshops > Stripe
   - Configurar API Keys (test y live)
   - Configurar webhook URL

4. **Configurar emails:**
   - Ir a Ajustes > KB Workshops > Email
   - Configurar SMTP o Brevo
   - Personalizar plantillas de email

5. **Configurar membresía:**
   - Ir a Ajustes > KB Membership
   - Configurar descuento premium (25% por defecto)
   - Personalizar emails de bienvenida

## 📊 Estructura de Base de Datos

### Tablas Personalizadas

```sql
-- KB Workshops
kb_workshop_bookings
- id, workshop_id, customer_email, customer_name
- quantity, total_amount, discount_amount
- stripe_session_id, stripe_payment_intent
- status, created_at, updated_at

-- KB Membership
kb_member_subscriptions
- id, user_id, stripe_subscription_id, stripe_customer_id
- status, current_period_start, current_period_end
- cancel_at_period_end, created_at, updated_at

kb_member_messages
- id, user_id, message, is_from_admin, is_read, created_at

kb_member_notifications
- id, user_id, type, title, message, is_read, created_at

-- KB Corporativo
kb_corporate_leads
- id, company_name, contact_name, email, phone
- min_participants, message, status, created_at, updated_at
```

## 🎨 Frontend y UX

### Diseño Responsive
- Mobile-first approach
- Grid layouts con CSS Grid y Flexbox
- Breakpoints: 480px, 768px, 1024px
- Animaciones suaves y transiciones

### Componentes Principales

#### Formulario de Reserva de Workshops
```html
[kb_workshop_booking_form]
```
- Selector de cantidad con actualización en tiempo real
- Cálculo automático de descuentos
- Upsell de membresía premium
- Validación en tiempo real
- Integración directa con Stripe Checkout

#### Panel de Miembro Premium
```html
[kb_member_dashboard]
```
- Próximos workshops reservados
- Gestión de suscripción
- Acceso a recursos premium
- Sistema de mensajería
- Notificaciones en tiempo real

#### Formulario Corporativo
```html
[kb_corporate_form]
```
- Campos: empresa, contacto, email, teléfono
- Selector de participantes mínimos
- Mensaje personalizado
- Notificaciones automáticas

#### Logo Wall
```html
[kb_logo_wall]
```
- Grid responsive de logos
- Enlaces opcionales
- Ordenamiento personalizable
- Bloque Gutenberg disponible

## 🔧 Configuración Avanzada

### Stripe Integration
```php
// Configuración en wp-config.php
define('STRIPE_TEST_PUBLISHABLE_KEY', 'pk_test_...');
define('STRIPE_TEST_SECRET_KEY', 'sk_test_...');
define('STRIPE_LIVE_PUBLISHABLE_KEY', 'pk_live_...');
define('STRIPE_LIVE_SECRET_KEY', 'sk_live_...');
```

### Email Configuration
```php
// SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');

// Brevo Configuration
define('BREVO_API_KEY', 'your-brevo-api-key');
```

### Custom Hooks
```php
// Hooks disponibles
add_action('kb_workshop_booking_completed', $callback, 10, 2);
add_action('kb_membership_subscription_created', $callback, 10, 2);
add_action('kb_corporate_lead_submitted', $callback, 10, 2);
```

## 📧 Sistema de Emails

### Plantillas Disponibles
- Confirmación de reserva de workshop
- Notificación de nueva reserva (admin)
- Bienvenida a membresía premium
- Cancelación de suscripción
- Recordatorio de workshop
- Confirmación de lead corporativo
- Notificación de lead corporativo (admin)

### Personalización
Todas las plantillas son editables desde el panel de administración:
- Asunto del email
- Contenido HTML
- Remitente y nombre
- Configuración SMTP/Brevo

## 🔒 Seguridad

### Medidas Implementadas
- Nonce verification en todas las operaciones AJAX
- Sanitización de inputs
- Validación de permisos de usuario
- Prepared statements en consultas SQL
- Encriptación de datos sensibles
- Rate limiting en formularios

### Roles y Capacidades
```php
// Roles personalizados
'premium_member' => [
    'premium_discount' => true,
    'view_premium_resources' => true,
    'send_messages' => true,
]

'workshop_manager' => [
    'manage_workshop_settings' => true,
    'view_workshop_bookings' => true,
    'manage_workshop_emails' => true,
]
```

## 📱 Responsive Design

### Breakpoints
```css
/* Mobile */
@media (max-width: 480px) { ... }

/* Tablet */
@media (max-width: 768px) { ... }

/* Desktop */
@media (min-width: 769px) { ... }
```

### Componentes Adaptativos
- Formularios con layout flexible
- Grid de logos responsive
- Panel de dashboard mobile-friendly
- Navegación optimizada para touch

## 🧪 Testing

### Funcionalidades Testeadas
- ✅ Reservas de workshops
- ✅ Cálculo de descuentos
- ✅ Integración Stripe
- ✅ Emails automáticos
- ✅ Panel de administración
- ✅ Responsive design
- ✅ Seguridad y validaciones

### Archivos de Test
```
tests/
├── test-stripe-integration.php
├── test-email-system.php
├── test-corporate-system.php
├── test-premium-system.php
└── test-database-security.php
```

## 📈 Analytics y Reportes

### Métricas Disponibles
- Reservas por workshop
- Ingresos totales y por período
- Membresías activas/canceladas
- Leads corporativos
- Conversión de upsell

### Panel de Reportes
- Dashboard con gráficos
- Exportación de datos
- Filtros por fecha y estado
- Métricas de rendimiento

## 🔄 Mantenimiento

### Actualizaciones
- Compatible con WordPress 6.0+
- Actualizaciones automáticas de base de datos
- Migración de datos segura
- Rollback en caso de errores

### Backup
- Backup automático de tablas personalizadas
- Exportación de configuraciones
- Logs de actividad
- Puntos de restauración

## 🌐 Internacionalización

### Idiomas Soportados
- Español (es_ES) - Principal
- Inglés (en_US) - Secundario

### Archivos de Traducción
```
languages/
├── kb-workshops-es_ES.po
├── kb-workshops-en_US.po
├── kb-membership-es_ES.po
├── kb-membership-en_US.po
├── kb-corporativo-es_ES.po
└── kb-corporativo-en_US.po
```

## 📞 Soporte

### Documentación
- Guías de instalación
- Manual de usuario
- API documentation
- Troubleshooting

### Contacto
- Email: soporte@katiebray.com
- Documentación: https://docs.katiebray.com
- GitHub: https://github.com/katiebray/plugins

## 📄 Licencia

Este proyecto está bajo la licencia GPL v2 o posterior.

---

**Desarrollado para Katie Bray**  
*Sistema completo de gestión de workshops y membresías premium* 