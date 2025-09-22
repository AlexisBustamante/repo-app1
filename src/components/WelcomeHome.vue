<template>
  <v-container fluid class="welcome-container">
    <!-- Hero Section -->
    <v-row class="hero-section" align="center" justify="center">
      <v-col cols="12" md="8" class="text-center">
        <div class="hero-content">
          <div class="hero-logo-container mb-6">
            <v-avatar size="140" class="hero-logo-avatar">
              <img src="../assets/logo.png" alt="Logo" />
            </v-avatar>
            <div class="hero-logo-badge">
              <v-icon color="white" size="24">mdi-shield-check</v-icon>
            </div>
          </div>
          
          <h1 class="text-h3 text-h2-md font-weight-bold mb-4 white--text">
            Bienvenido al Repositorio de Informes
          </h1>
          <p class="text-h6 white--text mb-8 opacity-90">
            Solución empresarial para la gestión profesional de documentos y reportes
          </p>
          
          <!-- Estadísticas rápidas -->
          <v-row justify="center" class="mb-8">
            <v-col cols="12" sm="4" v-for="stat in stats" :key="stat.title">
              <v-card class="stat-card" elevation="4">
                <v-card-text class="text-center pa-6">
                  <v-avatar :color="stat.color" size="60" class="mb-3">
                    <v-icon color="white" size="30">{{ stat.icon }}</v-icon>
                  </v-avatar>
                  <div class="text-h3 font-weight-bold" :class="stat.color + '--text'">
                    {{ stat.value }}
                  </div>
                  <div class="text-body-1 font-weight-medium text--secondary">
                    {{ stat.title }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-col>
    </v-row>

    <!-- Características principales -->
    <v-row class="features-section">
      <v-col cols="12">
        <div class="section-header text-center mb-12">
          <h2 class="text-h4 font-weight-bold mb-4 text--primary">
            ¿Cómo funciona nuestro sistema?
          </h2>
          <p class="text-h6 text--secondary max-width-600 mx-auto">
            Descubre cómo nuestra plataforma simplifica la gestión de documentos empresariales
          </p>
        </div>
        
        <v-timeline align-top dense>
          <v-timeline-item
            v-for="(item, i) in features"
            :key="i"
            :color="item.color"
            :icon="item.icon"
            fill-dot
            large
          >
            <template v-slot:opposite>
              <div class="step-indicator">
                <span class="step-number">{{ item.step }}</span>
              </div>
            </template>
            
            <v-card 
              class="feature-card"
              elevation="6"
              :class="'feature-card--' + item.color"
            >
              <v-card-title class="text-h6 d-flex align-center pa-6 pb-4">
                <v-avatar :color="item.color" size="50" class="mr-4">
                  <v-icon color="white" size="24">{{ item.icon }}</v-icon>
                </v-avatar>
                <div>
                  <div class="text-h6 font-weight-bold">{{ item.title }}</div>
                  <div class="text-caption text--secondary">{{ item.time }}</div>
                </div>
              </v-card-title>
              <v-card-text class="pa-6 pt-0">
                <p class="text-body-1 mb-0">
                  {{ item.description }}
                </p>
              </v-card-text>
            </v-card>
          </v-timeline-item>
        </v-timeline>
      </v-col>
    </v-row>

    <!-- Sección de beneficios -->
    <v-row class="benefits-section">
      <v-col cols="12">
        <div class="section-header text-center mb-12">
          <h2 class="text-h4 font-weight-bold mb-4 text--primary">
            Beneficios de nuestra plataforma
          </h2>
          <p class="text-h6 text--secondary max-width-600 mx-auto">
            Descubre las ventajas competitivas que ofrece nuestro sistema de gestión documental
          </p>
        </div>
        
        <v-row>
          <v-col 
            cols="12" 
            sm="6" 
            md="4" 
            v-for="benefit in benefits" 
            :key="benefit.title"
          >
            <v-card 
              class="benefit-card h-100" 
              elevation="4"
              hover
              :class="'benefit-card--' + benefit.color"
            >
              <v-card-text class="text-center pa-8">
                <div class="benefit-icon-container mb-4">
                  <v-avatar 
                    :color="benefit.color" 
                    size="80" 
                    class="benefit-icon"
                  >
                    <v-icon color="white" size="40">
                      {{ benefit.icon }}
                    </v-icon>
                  </v-avatar>
                </div>
                <h3 class="text-h6 font-weight-bold mb-4">
                  {{ benefit.title }}
                </h3>
                <p class="text-body-2 text--secondary">
                  {{ benefit.description }}
                </p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <!-- Call to Action -->
    <v-row class="cta-section">
      <v-col cols="12" class="text-center">
        <v-card 
          class="cta-card"
          elevation="12"
        >
          <v-card-text class="pa-12">
            <div class="cta-content">
              <v-avatar size="80" class="mb-6 cta-icon">
                <v-icon color="white" size="40">mdi-rocket-launch</v-icon>
              </v-avatar>
              
              <h2 class="text-h4 font-weight-bold mb-4 white--text">
                ¿Listo para comenzar?
              </h2>
              <p class="text-h6 white--text mb-8 opacity-90">
                Accede a nuestro repositorio empresarial y descubre la eficiencia en la gestión de documentos
              </p>
              
              <div class="cta-buttons">
                <v-btn 
                  color="white" 
                  large 
                  rounded
                  class="text-h6 px-8 mr-4 cta-btn"
                  elevation="4"
                >
                  <v-icon left>mdi-magnify</v-icon>
                  Explorar Documentos
                </v-btn>
                
                <v-btn 
                  color="transparent" 
                  large 
                  rounded
                  class="text-h6 px-8 cta-btn-outline"
                  outlined
                >
                  <v-icon left>mdi-information</v-icon>
                  Más Información
                </v-btn>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    stats: [
      {
        title: "Documentos Disponibles",
        value: "500+",
        color: "primary",
        icon: "mdi-file-document-multiple"
      },
      {
        title: "Categorías",
        value: "6",
        color: "info",
        icon: "mdi-folder-multiple"
      },
      {
        title: "Usuarios Activos",
        value: "50+",
        color: "success",
        icon: "mdi-account-group"
      }
    ],
    features: [
      {
        step: "01",
        color: "primary",
        icon: "mdi-magnify",
        title: "Búsqueda Inteligente",
        description: "Utiliza nuestro sistema de búsqueda avanzada para encontrar documentos por título, categoría, autor o contenido específico.",
        time: "Tiempo real"
      },
      {
        step: "02",
        color: "info",
        icon: "mdi-eye-check-outline",
        title: "Visualización Inmediata",
        description: "Previsualiza documentos directamente en el navegador con nuestra tecnología de renderizado optimizada.",
        time: "Instantáneo"
      },
      {
        step: "03",
        color: "success",
        icon: "mdi-download",
        title: "Descarga Segura",
        description: "Obtén el código fuente completo y archivos PDF de manera segura con nuestro sistema de descarga empresarial.",
        time: "Segundos"
      }
    ],
    benefits: [
      {
        title: "Acceso Instantáneo",
        description: "Encuentra cualquier documento en segundos con nuestro sistema de búsqueda inteligente y filtros avanzados.",
        color: "primary",
        icon: "mdi-lightning-bolt"
      },
      {
        title: "Organización Profesional",
        description: "Mantén todos tus documentos organizados por categorías, etiquetas y metadatos para un acceso eficiente.",
        color: "info",
        icon: "mdi-folder-cog"
      },
      {
        title: "Código Reutilizable",
        description: "Aprovecha el código fuente de documentos existentes para acelerar tu desarrollo y mantener consistencia.",
        color: "success",
        icon: "mdi-code-tags"
      },
      {
        title: "Colaboración Empresarial",
        description: "Comparte y colabora en documentos con tu equipo de manera segura y eficiente.",
        color: "warning",
        icon: "mdi-account-group"
      },
      {
        title: "Control de Versiones",
        description: "Mantén un historial completo de versiones de tus documentos para un mejor control y trazabilidad.",
        color: "secondary",
        icon: "mdi-history"
      },
      {
        title: "Seguridad Avanzada",
        description: "Tus documentos están protegidos con las mejores prácticas de seguridad empresarial y encriptación.",
        color: "error",
        icon: "mdi-shield-check"
      }
    ]
  }),
  methods: {},
  components: {},
  computed: {},
  created() {},
};
</script>

<style scoped>
.welcome-container {
  background: linear-gradient(135deg, #f5f5f5 0%, #e3f2fd 100%);
  min-height: 100vh;
}

/* Hero Section - Esquema azul corporativo */
.hero-section {
  padding: 6rem 0;
  background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
  color: white;
  margin: 0 !important;
  position: relative;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hero-pattern" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23hero-pattern)"/></svg>');
  opacity: 0.3;
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-logo-container {
  position: relative;
  display: inline-block;
}

.hero-logo-avatar {
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.hero-logo-badge {
  position: absolute;
  bottom: -8px;
  right: -8px;
  background: #4caf50;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 4px solid white;
  box-shadow: 0 6px 15px rgba(76, 175, 80, 0.4);
}

.stat-card {
  transition: all 0.3s ease;
  border-radius: 20px !important;
  background: rgba(255, 255, 255, 0.95) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(25, 118, 210, 0.2) !important;
}

/* Features Section */
.features-section {
  padding: 6rem 0;
  background: white;
  margin: 0 !important;
}

.section-header {
  margin-bottom: 4rem;
}

.max-width-600 {
  max-width: 600px;
}

.step-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  background: var(--primary-gradient);
  border-radius: 50%;
  box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
}

.step-number {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
}

.feature-card {
  border-radius: 20px !important;
  transition: all 0.3s ease;
  background: white !important;
  border: 1px solid #e0e0e0;
}

.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1) !important;
}

.feature-card--primary {
  border-left: 4px solid #1976d2;
}

.feature-card--info {
  border-left: 4px solid #2196f3;
}

.feature-card--success {
  border-left: 4px solid #4caf50;
}

/* Benefits Section */
.benefits-section {
  padding: 6rem 0;
  background: #f8f9fa;
  margin: 0 !important;
}

.benefit-card {
  border-radius: 20px !important;
  transition: all 0.3s ease;
  height: 100%;
  background: white !important;
  border: 1px solid #e0e0e0;
}

.benefit-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.benefit-icon-container {
  position: relative;
}

.benefit-icon {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.benefit-card:hover .benefit-icon {
  transform: scale(1.1);
}

.benefit-card--primary {
  border-top: 4px solid #1976d2;
}

.benefit-card--info {
  border-top: 4px solid #2196f3;
}

.benefit-card--success {
  border-top: 4px solid #4caf50;
}

.benefit-card--warning {
  border-top: 4px solid #ff9800;
}

.benefit-card--secondary {
  border-top: 4px solid #424242;
}

.benefit-card--error {
  border-top: 4px solid #f44336;
}

/* CTA Section */
.cta-section {
  padding: 6rem 0;
  background: var(--primary-gradient);
  margin: 0 !important;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="cta-pattern" width="80" height="80" patternUnits="userSpaceOnUse"><circle cx="40" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="60" cy="60" r="2" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23cta-pattern)"/></svg>');
  opacity: 0.3;
}

.cta-card {
  border-radius: 24px !important;
  background: var(--primary-gradient) !important;
  position: relative;
  z-index: 2;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.cta-content {
  position: relative;
  z-index: 3;
}

.cta-icon {
  background: rgba(255, 255, 255, 0.2) !important;
  backdrop-filter: blur(10px);
}

.cta-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.cta-btn {
  background: white !important;
  color: #1976d2 !important;
  font-weight: 600 !important;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
  transition: all 0.3s ease !important;
}

.cta-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15) !important;
}

.cta-btn-outline {
  border: 2px solid rgba(255, 255, 255, 0.8) !important;
  color: white !important;
  background: transparent !important;
  transition: all 0.3s ease !important;
}

.cta-btn-outline:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  transform: translateY(-2px) !important;
}

/* Animaciones */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card,
.feature-card,
.benefit-card {
  animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 960px) {
  .hero-section {
    padding: 4rem 0;
  }
  
  .features-section,
  .benefits-section,
  .cta-section {
    padding: 4rem 0;
  }
  
  .hero-section h1 {
    font-size: 2.5rem !important;
  }
  
  .hero-section p {
    font-size: 1.2rem !important;
  }
  
  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .cta-btn,
  .cta-btn-outline {
    width: 100%;
    max-width: 300px;
  }
}

@media (max-width: 600px) {
  .hero-section {
    padding: 3rem 0;
  }
  
  .features-section,
  .benefits-section,
  .cta-section {
    padding: 3rem 0;
  }
  
  .hero-section h1 {
    font-size: 2rem !important;
  }
  
  .hero-section p {
    font-size: 1.1rem !important;
  }
  
  .stat-card .v-card-text {
    padding: 1.5rem !important;
  }
  
  .benefit-card .v-card-text {
    padding: 1.5rem !important;
  }
  
  .cta-card .v-card-text {
    padding: 2rem !important;
  }
}

/* Tema oscuro */
.theme--dark .welcome-container {
  background: linear-gradient(135deg, #121212 0%, #1e1e1e 100%);
}

.theme--dark .features-section {
  background: #1e1e1e;
}

.theme--dark .benefits-section {
  background: #2d2d2d;
}

.theme--dark .feature-card,
.theme--dark .benefit-card {
  background: #2d2d2d !important;
  border-color: #424242;
}

.theme--dark .stat-card {
  background: rgba(45, 45, 45, 0.95) !important;
  border-color: #424242;
}
</style>
