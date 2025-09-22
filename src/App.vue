<template>
  <v-app id="inspire">
    <!-- <v-system-bar :color="'#11334d'" app>
      <v-spacer></v-spacer>
      <v-icon>mdi-square</v-icon>
      <v-icon>mdi-circle</v-icon>
      <v-icon>mdi-triangle</v-icon>
    </v-system-bar> -->

    <!-- DIALOG VER CÓDIGO PHP MEJORADO -->
    <v-dialog
      v-model="dialogShowphp"
      transition="dialog-top-transition"
      max-width="1000"
      class="code-dialog"
    >
      <v-card class="code-dialog-card">
        <!-- Header del diálogo -->
        <v-card-title class="code-dialog-header">
          <div class="d-flex align-center">
            <v-avatar color="purple" size="40" class="mr-3">
              <v-icon color="white">mdi-code-tags</v-icon>
            </v-avatar>
            <div>
              <h2 class="text-h5 font-weight-bold mb-0">Código PHP</h2>
              <p class="text-caption mb-0 opacity-75">Código fuente del documento seleccionado</p>
            </div>
          </div>
          <v-btn
            icon
            @click="dialogShowphp = false"
            class="ml-auto"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-divider></v-divider>

        <!-- Información del documento -->
        <v-card-text class="code-dialog-info pa-4" v-if="itemSelected.title">
          <v-row align="center">
            <v-col cols="12" sm="8">
              <div class="d-flex align-center">
                <v-avatar :color="getCategoryColor(itemSelected.category)" size="40" class="mr-3">
                  <v-icon color="white" size="20">{{ getCategoryIcon(itemSelected.category) }}</v-icon>
                </v-avatar>
                <div>
                  <h3 class="text-h6 font-weight-bold mb-1">{{ itemSelected.title }}</h3>
                  <p class="text-caption text--secondary mb-0">
                    {{ itemSelected.category }} • {{ itemSelected.author }}
                  </p>
                </div>
              </div>
            </v-col>
            <v-col cols="12" sm="4" class="text-right">
              <v-btn
                color="primary"
                small
                @click="copyToClipboard"
                class="mr-2"
              >
                <v-icon left small>mdi-content-copy</v-icon>
                Copiar
              </v-btn>
              <v-btn
                color="success"
                small
                @click="downloadCode"
              >
                <v-icon left small>mdi-download</v-icon>
                Descargar
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>

        <!-- Editor de código -->
        <v-card-text class="code-dialog-content pa-0">
          <div class="code-editor-container">
            <div class="code-editor-header">
              <v-chip color="purple" small class="ma-2">
                <v-icon left small>mdi-language-php</v-icon>
                PHP
              </v-chip>
              <v-spacer></v-spacer>
              <v-chip color="grey" small class="ma-2">
                {{ contentEditor.length }} caracteres
              </v-chip>
            </div>
            <div class="code-editor-wrapper">
              <vue-editor
                :editorToolbar="customToolbar"
                v-model="contentEditor"
                class="code-editor"
                :readonly="true"
              ></vue-editor>
            </div>
          </div>
        </v-card-text>

        <!-- Actions -->
        <v-card-actions class="code-dialog-actions pa-4">
          <v-spacer></v-spacer>
          <v-btn
            @click="dialogShowphp = false"
            color="primary"
            class="px-6"
          >
            <v-icon left>mdi-check</v-icon>
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- DIALOG EDITAR DOCUMENTO MEJORADO -->
    <v-dialog 
      v-model="dialogEdit" 
      persistent 
      max-width="1000px"
      scrollable
      class="document-dialog"
    >
      <v-card class="dialog-card">
        <!-- Header del diálogo -->
        <v-card-title class="dialog-header">
          <div class="d-flex align-center">
            <v-avatar color="warning" size="40" class="mr-3">
              <v-icon color="white">mdi-pencil</v-icon>
            </v-avatar>
            <div>
              <h2 class="text-h5 font-weight-bold mb-0">Editar Documento</h2>
              <p class="text-caption mb-0 opacity-75">Modifica la información del documento seleccionado</p>
            </div>
          </div>
          <v-btn
            icon
            @click="cancelDialogEdit()"
            class="ml-auto"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-divider></v-divider>

        <!-- Loading State -->
        <v-card-text v-if="loading" class="loading-container">
          <v-row class="fill-height" align-content="center" justify="center">
            <v-col cols="12" class="text-center">
              <v-progress-circular
                indeterminate
                color="primary"
                size="64"
                width="6"
                class="mb-4"
              ></v-progress-circular>
              <h3 class="text-h6 mb-2">Actualizando documento...</h3>
              <p class="text-body-2 text--secondary">
                Por favor espera mientras procesamos los cambios
              </p>
            </v-col>
          </v-row>
        </v-card-text>

        <!-- Form Content -->
        <v-card-text v-else class="dialog-content">
          <v-form ref="formEditDocument" v-model="formValid">
            <v-row>
              <!-- Formulario principal -->
              <v-col cols="12" :md="enabledPHP ? 6 : 12">
                <v-card flat class="form-section">
                  <v-card-title class="text-h6 pa-0 mb-4">
                    <v-icon class="mr-2">mdi-information</v-icon>
                    Información del Documento
                  </v-card-title>
                  
                  <v-text-field
                    v-model="title"
                    label="Título del documento"
                    outlined
                    prepend-inner-icon="mdi-text"
                    :rules="titleRules"
                    required
                    class="mb-4"
                    hint="Ingresa un título descriptivo para el documento"
                    persistent-hint
                  ></v-text-field>

                  <v-row>
                    <v-col cols="12" sm="6">
                      <v-select
                        :items="itemsAuthors"
                        label="Autor"
                        outlined
                        prepend-inner-icon="mdi-account"
                        v-model="selectA"
                        @change="(valor) => changeStateA(valor)"
                        :rules="authorRules"
                        required
                        class="mb-4"
                      ></v-select>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-select
                        :items="itemsCategoria"
                        v-model="selectC"
                        label="Categoría"
                        outlined
                        prepend-inner-icon="mdi-folder"
                        @change="(valor) => changeState(valor)"
                        :rules="categoryRules"
                        required
                        class="mb-4"
                      ></v-select>
                    </v-col>
                  </v-row>

                  <v-text-field
                    v-model="ticket"
                    label="Ticket Relacionado"
                    outlined
                    prepend-inner-icon="mdi-ticket"
                    class="mb-4"
                    hint="ID del ticket o referencia relacionada"
                    persistent-hint
                  ></v-text-field>

                  <v-textarea
                    v-model="description"
                    label="Descripción"
                    outlined
                    prepend-inner-icon="mdi-text-subject"
                    :rules="descriptionRules"
                    required
                    rows="3"
                    class="mb-4"
                    hint="Describe brevemente el contenido del documento"
                    persistent-hint
                  ></v-textarea>

                  <!-- Sección de archivo PDF -->
                  <v-card flat class="file-section mb-4">
                    <v-card-title class="text-subtitle-1 pa-0 mb-3">
                      <v-icon class="mr-2">mdi-file-pdf-box</v-icon>
                      Archivo PDF
                    </v-card-title>
                    <v-row>
                      <v-col cols="12" sm="8">
                        <v-file-input
                          v-model="filePDFData"
                          prepend-inner-icon="mdi-file-pdf-box"
                          label="Nuevo archivo PDF (opcional)"
                          outlined
                          chips
                          show-size
                          accept=".pdf"
                          @change="assignDataPDF"
                          :disabled="!enabledPDF"
                          hint="Selecciona un nuevo archivo PDF para reemplazar el actual"
                          persistent-hint
                        ></v-file-input>
                      </v-col>
                      <v-col cols="12" sm="4" class="d-flex align-center">
                        <v-checkbox
                          v-model="enabledPDF"
                          label="Reemplazar archivo"
                          color="primary"
                          hide-details
                        ></v-checkbox>
                      </v-col>
                    </v-row>
                  </v-card>
                </v-card>
              </v-col>

              <!-- Editor PHP (si aplica) -->
              <v-col cols="12" md="6" v-if="enabledPHP">
                <v-card flat class="form-section">
                  <v-card-title class="text-h6 pa-0 mb-4">
                    <v-icon class="mr-2">mdi-code-tags</v-icon>
                    Código PHP
                  </v-card-title>
                  <v-card-subtitle class="pa-0 mb-4">
                    Modifica el código PHP que se ejecutará para generar el documento
                  </v-card-subtitle>
                  
                  <vue-editor
                    :editorToolbar="customToolbar"
                    v-model="contentEditor"
                    class="php-editor"
                  ></vue-editor>
                </v-card>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <!-- Actions -->
        <v-card-actions class="dialog-actions" v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn
            @click="cancelDialogEdit()"
            text
            color="grey"
            class="px-6"
          >
            <v-icon left>mdi-close</v-icon>
            Cancelar
          </v-btn>
          <v-btn
            @click="editDocument()"
            color="warning"
            :disabled="!formValid"
            class="px-6"
            :loading="loading"
          >
            <v-icon left>mdi-content-save</v-icon>
            Actualizar Documento
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- DIALOG ELIMINAR DOCUMENTO MEJORADO -->
    <v-dialog
      v-model="dialogDel"
      transition="dialog-top-transition"
      max-width="500"
      class="delete-dialog"
    >
      <v-card class="delete-dialog-card">
        <!-- Header del diálogo -->
        <v-card-title class="delete-dialog-header">
          <div class="d-flex align-center">
            <v-avatar color="error" size="50" class="mr-4">
              <v-icon color="white" size="28">mdi-delete-alert</v-icon>
            </v-avatar>
            <div>
              <h2 class="text-h5 font-weight-bold mb-0">Confirmar Eliminación</h2>
              <p class="text-caption mb-0 opacity-75">Esta acción no se puede deshacer</p>
            </div>
          </div>
        </v-card-title>

        <v-divider></v-divider>

        <!-- Contenido del diálogo -->
        <v-card-text class="delete-dialog-content pa-6">
          <div class="text-center mb-6">
            <v-icon color="error" size="64" class="mb-4">mdi-alert-circle</v-icon>
            <h3 class="text-h6 font-weight-bold mb-3">
              ¿Estás seguro de que deseas eliminar este documento?
            </h3>
            <p class="text-body-1 text--secondary mb-4">
              <strong>{{ itemSelected.title }}</strong>
            </p>
            <p class="text-body-2 text--secondary">
              Esta acción eliminará permanentemente el documento y todos sus archivos asociados. 
              No podrás recuperar esta información.
            </p>
          </div>

          <!-- Información del documento a eliminar -->
          <v-card flat class="document-preview pa-4 mb-4" color="grey lighten-5">
            <div class="d-flex align-center">
              <v-avatar :color="getCategoryColor(itemSelected.category)" size="40" class="mr-3">
                <v-icon color="white" size="20">{{ getCategoryIcon(itemSelected.category) }}</v-icon>
              </v-avatar>
              <div class="flex-grow-1">
                <div class="text-subtitle-2 font-weight-bold">{{ itemSelected.title }}</div>
                <div class="text-caption text--secondary">
                  {{ itemSelected.category }} • {{ itemSelected.author }}
                </div>
              </div>
            </div>
          </v-card>

          <!-- Advertencia adicional -->
          <v-alert
            type="warning"
            outlined
            class="mb-0"
          >
            <v-icon left>mdi-alert</v-icon>
            <strong>Advertencia:</strong> Esta acción es irreversible. Asegúrate de haber respaldado cualquier información importante.
          </v-alert>
        </v-card-text>

        <!-- Actions -->
        <v-card-actions class="delete-dialog-actions pa-6">
          <v-spacer></v-spacer>
          <v-btn
            @click="dialogDel = false"
            text
            color="grey"
            class="px-6"
          >
            <v-icon left>mdi-close</v-icon>
            Cancelar
          </v-btn>
          <v-btn
            @click="DeleteDoc"
            color="error"
            class="px-6"
            elevation="2"
          >
            <v-icon left>mdi-delete</v-icon>
            Eliminar Definitivamente
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- LOGIN MEJORADO -->
    <v-dialog
      v-model="dialogLogin"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card class="login-container">
        <v-row no-gutters class="fill-height">
          <!-- Panel Izquierdo - Formulario -->
          <v-col cols="12" md="6" class="d-flex align-center justify-center pa-8">
            <v-card flat class="login-form-card" max-width="480" elevation="8">
              <v-card-text class="pa-8">
                <!-- Header del formulario -->
                <div class="text-center mb-8">
                  <div class="login-logo-container mb-6">
                    <v-avatar size="80" class="login-logo-avatar">
                      <img src="./assets/logo_app.png" alt="Logo" />
                    </v-avatar>
                    <div class="login-logo-badge">
                      <v-icon color="white" size="20">mdi-shield-check</v-icon>
                    </div>
                  </div>
                  <h1 class="text-h4 font-weight-bold mb-3 text--primary">
                    Bienvenido de vuelta
                  </h1>
                  <p class="text-body-1 text--secondary mb-0">
                    Inicia sesión para acceder a tu repositorio de informes
                  </p>
                </div>

                <!-- Formulario de login -->
                <v-form @submit.prevent="signIn()" ref="loginForm" v-model="loginFormValid">
                  <v-text-field
                    v-model="email"
                    label="Correo electrónico"
                    type="email"
                    outlined
                    prepend-inner-icon="mdi-email-outline"
                    color="primary"
                    class="mb-4"
                    :rules="emailRules"
                    required
                    :error-messages="emailError"
                    @input="emailError = ''"
                  />
                  
                  <v-text-field
                    v-model="password"
                    label="Contraseña"
                    :type="showPassword ? 'text' : 'password'"
                    outlined
                    prepend-inner-icon="mdi-lock-outline"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showPassword = !showPassword"
                    color="primary"
                    class="mb-6"
                    :rules="passwordRules"
                    required
                    :error-messages="passwordError"
                    @input="passwordError = ''"
                  />

                  <!-- Recordar contraseña -->
                  <div class="d-flex justify-space-between align-center mb-6">
                    <v-checkbox
                      v-model="rememberMe"
                      label="Recordar contraseña"
                      color="primary"
                      hide-details
                      dense
                    ></v-checkbox>
                    <v-btn
                      text
                      small
                      color="primary"
                      class="text-caption"
                    >
                      ¿Olvidaste tu contraseña?
                    </v-btn>
                  </div>

                  <!-- Botón de login -->
                  <v-btn
                    type="submit"
                    color="primary"
                    large
                    block
                    rounded
                    class="text-h6 py-6 mb-4 login-btn"
                    :loading="loginLoading"
                    :disabled="!loginFormValid"
                    elevation="2"
                  >
                    <v-icon left>mdi-login-variant</v-icon>
                    Iniciar Sesión
                  </v-btn>

                  <!-- Alertas de error -->
                  <v-alert
                    v-model="dialogErr"
                    type="error"
                    dismissible
                    class="mb-4"
                    border="left"
                    colored-border
                    elevation="2"
                  >
                    <v-icon left>mdi-alert-circle</v-icon>
                    {{ errorMessage }}
                  </v-alert>

                  <!-- Información adicional -->
                  <div class="text-center">
                    <p class="text-caption text--secondary">
                      Al iniciar sesión, aceptas nuestros 
                      <a href="#" class="text-decoration-none">términos de servicio</a>
                      y 
                      <a href="#" class="text-decoration-none">política de privacidad</a>
                    </p>
                  </div>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Panel Derecho - Información Corporativa -->
          <v-col cols="12" md="6" class="login-info-panel d-none d-md-flex">
            <div class="login-info-content">
              <!-- Fondo decorativo -->
              <div class="login-bg-pattern"></div>
              
              <div class="text-center">
                <!-- Logo principal -->
                <div class="login-main-logo mb-8">
                  <v-avatar size="120" class="mb-4">
                    <img src="./assets/logo.png" alt="Logo Empresa" />
                  </v-avatar>
                  <h1 class="text-h3 font-weight-bold white--text mb-4">
                    Repositorio de Informes
                  </h1>
                  <p class="text-h6 white--text mb-8 opacity-90">
                    Solución empresarial para la gestión profesional de documentos
                  </p>
                </div>
                
                <!-- Características principales -->
                <v-row justify="center" class="mt-8">
                  <v-col cols="10">
                    <v-card class="pa-6 features-card" elevation="4">
                      <v-row>
                        <v-col cols="12" sm="4" class="text-center mb-4">
                          <v-avatar color="primary" size="60" class="mb-3">
                            <v-icon color="white" size="30">mdi-file-document-multiple</v-icon>
                          </v-avatar>
                          <h3 class="text-h6 font-weight-bold mb-2">Documentos</h3>
                          <p class="text-caption mb-0">Gestión centralizada</p>
                        </v-col>
                        <v-col cols="12" sm="4" class="text-center mb-4">
                          <v-avatar color="success" size="60" class="mb-3">
                            <v-icon color="white" size="30">mdi-shield-check</v-icon>
                          </v-avatar>
                          <h3 class="text-h6 font-weight-bold mb-2">Seguridad</h3>
                          <p class="text-caption mb-0">Protección avanzada</p>
                        </v-col>
                        <v-col cols="12" sm="4" class="text-center mb-4">
                          <v-avatar color="info" size="60" class="mb-3">
                            <v-icon color="white" size="30">mdi-cloud-sync</v-icon>
                          </v-avatar>
                          <h3 class="text-h6 font-weight-bold mb-2">Sincronización</h3>
                          <p class="text-caption mb-0">Acceso en tiempo real</p>
                        </v-col>
                      </v-row>
                    </v-card>
                  </v-col>
                </v-row>

                <!-- Estadísticas -->
                <v-row justify="center" class="mt-6">
                  <v-col cols="10">
                    <v-row>
                      <v-col cols="4" class="text-center">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Documentos</div>
                      </v-col>
                      <v-col cols="4" class="text-center">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Usuarios</div>
                      </v-col>
                      <v-col cols="4" class="text-center">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Disponibilidad</div>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-dialog>

    <!-- SNACKVAR PARA MENSAJES -->
    <v-snackbar
      :timeout="timeout"
      v-model="snackbar"
      :vertical="vertical"
      :color="snacktype.color"
    >
      <p>
        <v-icon>{{ snacktype.icon }}</v-icon
        >{{ snacktype.msg }}.
      </p>
    </v-snackbar>

    <!-- DIALOG NUEVO DOCUMENTO MEJORADO -->
    <v-dialog 
      v-model="dialog" 
      persistent 
      max-width="1000px"
      scrollable
      class="document-dialog"
    >
      <v-card class="dialog-card">
        <!-- Header del diálogo -->
        <v-card-title class="dialog-header">
          <div class="d-flex align-center">
            <v-avatar color="primary" size="40" class="mr-3">
              <v-icon color="white">mdi-file-plus</v-icon>
            </v-avatar>
            <div>
              <h2 class="text-h5 font-weight-bold mb-0">Nuevo Documento</h2>
              <p class="text-caption mb-0 opacity-75">Crea un nuevo documento en el repositorio</p>
            </div>
          </div>
          <v-btn
            icon
            @click="cancelDialog()"
            class="ml-auto"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-divider></v-divider>

        <!-- Loading State -->
        <v-card-text v-if="loading" class="loading-container">
          <v-row class="fill-height" align-content="center" justify="center">
            <v-col cols="12" class="text-center">
              <v-progress-circular
                indeterminate
                color="primary"
                size="64"
                width="6"
                class="mb-4"
              ></v-progress-circular>
              <h3 class="text-h6 mb-2">Subiendo archivos a la nube...</h3>
              <p class="text-body-2 text--secondary">
                Por favor espera mientras procesamos tu documento
              </p>
            </v-col>
          </v-row>
        </v-card-text>

        <!-- Form Content -->
        <v-card-text v-else class="dialog-content">
          <v-form ref="formNewDocument" v-model="formValid">
            <v-row>
              <!-- Formulario principal -->
              <v-col cols="12" :md="enabledPHP ? 6 : 12">
                <v-card flat class="form-section">
                  <v-card-title class="text-h6 pa-0 mb-4">
                    <v-icon class="mr-2">mdi-information</v-icon>
                    Información del Documento
                  </v-card-title>
                  
                  <v-text-field
                    v-model="title"
                    label="Título del documento"
                    outlined
                    prepend-inner-icon="mdi-text"
                    :rules="titleRules"
                    required
                    class="mb-4"
                    hint="Ingresa un título descriptivo para el documento"
                    persistent-hint
                  ></v-text-field>

                  <v-row>
                    <v-col cols="12" sm="6">
                      <v-select
                        :items="itemsAuthors"
                        label="Autor"
                        outlined
                        prepend-inner-icon="mdi-account"
                        @change="(valor) => changeStateA(valor)"
                        :rules="authorRules"
                        required
                        class="mb-4"
                      ></v-select>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-select
                        :items="itemsCategoria"
                        label="Categoría"
                        outlined
                        prepend-inner-icon="mdi-folder"
                        @change="(valor) => changeState(valor)"
                        :rules="categoryRules"
                        required
                        class="mb-4"
                      ></v-select>
                    </v-col>
                  </v-row>

                  <v-text-field
                    v-model="ticket"
                    label="Ticket Relacionado"
                    outlined
                    prepend-inner-icon="mdi-ticket"
                    class="mb-4"
                    hint="ID del ticket o referencia relacionada"
                    persistent-hint
                  ></v-text-field>

                  <v-textarea
                    v-model="description"
                    label="Descripción"
                    outlined
                    prepend-inner-icon="mdi-text-subject"
                    :rules="descriptionRules"
                    required
                    rows="3"
                    class="mb-4"
                    hint="Describe brevemente el contenido del documento"
                    persistent-hint
                  ></v-textarea>

                  <v-file-input
                    v-model="filePDFData"
                    prepend-inner-icon="mdi-file-pdf-box"
                    label="Archivo PDF"
                    outlined
                    chips
                    show-size
                    accept=".pdf"
                    @change="assignDataPDF"
                    :rules="fileRules"
                    required
                    class="mb-4"
                    hint="Selecciona el archivo PDF del documento"
                    persistent-hint
                  ></v-file-input>
                </v-card>
              </v-col>

              <!-- Editor PHP (si aplica) -->
              <v-col cols="12" md="6" v-if="enabledPHP">
                <v-card flat class="form-section">
                  <v-card-title class="text-h6 pa-0 mb-4">
                    <v-icon class="mr-2">mdi-code-tags</v-icon>
                    Código PHP
                  </v-card-title>
                  <v-card-subtitle class="pa-0 mb-4">
                    Agrega el código PHP que se ejecutará para generar el documento
                  </v-card-subtitle>
                  
                  <vue-editor
                    :editorToolbar="customToolbar"
                    v-model="contentEditor"
                    class="php-editor"
                  ></vue-editor>
                </v-card>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <!-- Actions -->
        <v-card-actions class="dialog-actions" v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn
            @click="cancelDialog()"
            text
            color="grey"
            class="px-6"
          >
            <v-icon left>mdi-close</v-icon>
            Cancelar
          </v-btn>
          <v-btn
            @click="SaveNewDoc()"
            color="primary"
            :disabled="!formValid"
            class="px-6"
            :loading="loading"
          >
            <v-icon left>mdi-content-save</v-icon>
            Guardar Documento
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-app-bar 
      color="primary" 
      dark 
      app 
      clipped-right 
      elevation="2"
      height="70"
      class="app-bar-modern"
    >
      <v-container class="d-flex align-center pa-0">
        <v-row no-gutters class="align-center">
          <v-col cols="auto" class="d-flex align-center">
            <v-btn
              icon
              @click="drawer = !drawer"
              class="mr-3"
            >
              <v-icon>mdi-menu</v-icon>
            </v-btn>
            
            <div class="d-flex align-center">
              <v-avatar size="40" class="mr-3">
                <img src="./assets/logo_app.png" alt="Logo" />
              </v-avatar>
              <div>
                <h1 class="text-h6 font-weight-bold mb-0">
                  {{ pdfsrc != "" ? itemSelected.title : "Repositorio de Informes" }}
                </h1>
                <p class="text-caption mb-0 opacity-75">
                  {{ pdfsrc != "" ? "Visualizando documento" : "Gestión de documentos" }}
                </p>
              </div>
            </div>
          </v-col>

          <v-spacer></v-spacer>

          <v-col cols="auto" class="d-flex align-center">
            <!-- Botón de búsqueda global -->
            <v-btn
              icon
              @click="toggleGlobalSearch"
              class="mr-2"
              :color="showGlobalSearch ? 'accent' : 'white'"
            >
              <v-icon>mdi-magnify</v-icon>
            </v-btn>

            <!-- Notificaciones -->
            <v-btn
              icon
              class="mr-2"
              @click="showNotifications = !showNotifications"
            >
              <v-badge
                :content="notificationCount"
                :value="notificationCount"
                color="red"
                overlap
              >
                <v-icon>mdi-bell</v-icon>
              </v-badge>
            </v-btn>

            <!-- Toggle tema oscuro -->
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-switch
                  v-bind="attrs"
                  v-on="on"
                  v-model="$vuetify.theme.dark"
                  inset
                  class="theme-switch"
                  color="accent"
                  hide-details
                ></v-switch>
              </template>
              <span>{{ $vuetify.theme.dark ? 'Modo claro' : 'Modo oscuro' }}</span>
            </v-tooltip>

            <!-- Menú de usuario -->
            <v-menu
              bottom
              offset-y
              left
              min-width="200"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  v-bind="attrs"
                  v-on="on"
                  class="ml-3"
                >
                  <v-avatar size="36">
                    <v-icon>mdi-account-circle</v-icon>
                  </v-avatar>
                </v-btn>
              </template>
              
              <v-card>
                <v-list>
                  <v-list-item>
                    <v-list-item-avatar>
                      <v-icon>mdi-account-circle</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>{{ user.fullName }}</v-list-item-title>
                      <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  
                  <v-divider></v-divider>
                  
                  <v-list-item @click="signOut()">
                    <v-list-item-icon>
                      <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Cerrar Sesión</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card>
            </v-menu>
          </v-col>
        </v-row>
      </v-container>

      <!-- Búsqueda global expandible -->
      <v-expand-transition>
        <div v-show="showGlobalSearch" class="global-search-panel">
          <v-container>
            <v-text-field
              v-model="globalSearchQuery"
              placeholder="Buscar en todos los documentos..."
              prepend-inner-icon="mdi-magnify"
              append-icon="mdi-close"
              @click:append="toggleGlobalSearch"
              @keyup.enter="performGlobalSearch"
              hide-details
              solo
              flat
              class="global-search-field"
            ></v-text-field>
          </v-container>
        </div>
      </v-expand-transition>
    </v-app-bar>
    <!-- SIDEBAR IZQUIERDO MEJORADO -->
    <v-navigation-drawer 
      v-model="drawer" 
      app 
      width="380"
      class="sidebar-modern"
      :mini-variant="miniSidebar"
      :permanent="!$vuetify.breakpoint.mobile"
    >
      <!-- Header del sidebar -->
      <v-card flat class="sidebar-header" color="primary" dark>
        <v-card-text class="pa-4">
          <div class="d-flex align-center">
            <v-avatar size="50" class="mr-3">
              <img src="./assets/logo_app.png" alt="Logo" />
            </v-avatar>
            <div v-if="!miniSidebar">
              <h3 class="text-h6 font-weight-bold mb-0">Repositorio</h3>
              <p class="text-caption mb-0 opacity-75">Gestión de Informes</p>
            </div>
          </div>
        </v-card-text>
      </v-card>

      <!-- Panel de búsqueda y filtros -->
      <v-card flat class="search-panel" v-if="!miniSidebar">
        <v-card-text class="pa-4">
          <v-text-field
            v-model="search"
            prepend-inner-icon="mdi-magnify"
            label="Buscar documentos..."
            outlined
            dense
            clearable
            hide-details
            class="mb-3"
          ></v-text-field>
          
          <v-combobox
            v-model="chips"
            :items="itemsCategory"
            chips
            clearable
            label="Filtrar por categoría"
            multiple
            outlined
            dense
            hide-details
          >
            <template v-slot:selection="{ attrs, item, select, selected }">
              <v-chip
                v-bind="attrs"
                :input-value="selected"
                close
                color="primary"
                small
                @click="select"
                @click:close="remove(item)"
              >
                {{ item }}
              </v-chip>
            </template>
          </v-combobox>

          <div class="d-flex justify-space-between align-center mt-3">
            <v-btn
              icon
              @click="getDocuments()"
              color="primary"
              small
            >
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
            <span class="text-caption text--secondary">
              {{ searching.length }} documentos
            </span>
          </div>
        </v-card-text>
      </v-card>

      <!-- Botones de acción rápida -->
      <v-card flat class="action-buttons" v-if="!miniSidebar">
        <v-card-text class="pa-2">
          <v-row no-gutters>
            <v-col cols="4" class="pa-1">
              <v-btn
                @click="addFileDialog()"
                color="success"
                block
                small
                class="action-btn"
              >
                <v-icon left small>mdi-plus</v-icon>
                Nuevo
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-1">
              <v-btn
                @click="editFileDialog()"
                :disabled="itemSelected.title == ''"
                color="primary"
                block
                small
                class="action-btn"
              >
                <v-icon left small>mdi-pencil</v-icon>
                Editar
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-1">
              <v-btn
                @click="delFileDialog()"
                :disabled="itemSelected.title == ''"
                color="error"
                block
                small
                class="action-btn"
              >
                <v-icon left small>mdi-delete</v-icon>
                Eliminar
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <!-- Lista de documentos -->
      <v-card flat class="documents-list">
        <v-list dense>
          <v-list-item
            v-for="(item) in searching"
            :key="item.id"
            @click="wacthpdf(item)"
            :class="{
              'document-item': true,
              'document-item--active': item.id === itemSelected.id
            }"
          >
            <v-list-item-avatar>
              <v-icon :color="getCategoryColor(item.category)">
                {{ getCategoryIcon(item.category) }}
              </v-icon>
            </v-list-item-avatar>
            
            <v-list-item-content>
              <v-list-item-title class="text-body-2 font-weight-medium">
                {{ item.title }}
              </v-list-item-title>
              <v-list-item-subtitle class="text-caption">
                {{ item.description.length > 50 ? item.description.substring(0, 50) + '...' : item.description }}
              </v-list-item-subtitle>
              <div class="d-flex align-center mt-1">
                <v-chip
                  :color="getCategoryColor(item.category)"
                  x-small
                  class="mr-2"
                >
                  {{ item.category }}
                </v-chip>
                <v-chip
                  color="grey lighten-2"
                  x-small
                  text-color="grey darken-2"
                >
                  {{ item.author }}
                </v-chip>
              </div>
            </v-list-item-content>

            <v-list-item-action>
              <v-icon size="16" color="grey">
                mdi-chevron-right
              </v-icon>
            </v-list-item-action>
          </v-list-item>

          <!-- Estado vacío -->
          <v-list-item v-if="searching.length === 0">
            <v-list-item-content class="text-center py-8">
              <v-icon size="48" color="grey lighten-1" class="mb-2">
                mdi-file-document-outline
              </v-icon>
              <p class="text-body-2 text--secondary mb-0">
                {{ search ? 'No se encontraron documentos' : 'No hay documentos disponibles' }}
              </p>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>

      <!-- Botón para colapsar/expandir sidebar -->
      <v-card flat class="sidebar-toggle">
        <v-card-text class="pa-2 text-center">
          <v-btn
            icon
            small
            @click="miniSidebar = !miniSidebar"
            color="primary"
          >
            <v-icon>{{ miniSidebar ? 'mdi-chevron-right' : 'mdi-chevron-left' }}</v-icon>
          </v-btn>
        </v-card-text>
      </v-card>
    </v-navigation-drawer>

    <!-- SIDEBAR DERECHO MEJORADO CON COLAPSO -->
    <v-navigation-drawer
      v-model="drawerRight"
      app
      clipped
      right
      :width="miniRight ? 80 : 400"
      class="sidebar-right-modern"
      :class="{ 'sidebar-right-collapsed': miniRight }"
    >
      <!-- Header del sidebar derecho -->
      <v-card flat class="sidebar-right-header" color="primary" dark>
        <v-card-text class="pa-4">
          <div class="d-flex align-center justify-space-between">
            <div class="d-flex align-center">
              <v-avatar color="white" size="40" class="mr-3">
                <v-icon color="primary">mdi-file-document</v-icon>
              </v-avatar>
              <div v-if="!miniRight">
                <h3 class="text-h6 font-weight-bold mb-0">Detalles</h3>
                <p class="text-caption mb-0 opacity-75">Información del documento</p>
              </div>
            </div>
            <v-btn
              icon
              small
              @click="miniRight = !miniRight"
              color="white"
            >
              <v-icon>{{ miniRight ? 'mdi-chevron-left' : 'mdi-chevron-right' }}</v-icon>
            </v-btn>
          </div>
        </v-card-text>
      </v-card>

      <!-- Contenido del sidebar -->
      <v-card flat class="sidebar-right-content" v-if="!miniRight">
        <v-card-text class="pa-0">
          <!-- Estado vacío cuando no hay documento seleccionado -->
          <div v-if="!itemSelected.title" class="empty-state">
            <div class="text-center pa-8">
              <v-icon size="80" color="grey lighten-2" class="mb-4">mdi-file-search-outline</v-icon>
              <h3 class="text-h6 font-weight-bold mb-2 text--secondary">¡Bienvenido!</h3>
              <p class="text-body-2 text--secondary mb-6">
                Selecciona un documento de la lista para ver sus detalles, información y opciones de edición aquí.
              </p>
              <v-card flat class="pa-4 mb-4" color="blue lighten-5">
                <div class="d-flex align-center">
                  <v-icon color="primary" class="mr-3">mdi-information</v-icon>
                  <div class="text-left">
                    <div class="text-subtitle-2 font-weight-bold">Tip:</div>
                    <div class="text-caption">Usa la búsqueda global o filtra por categoría para encontrar documentos rápidamente.</div>
                  </div>
                </div>
              </v-card>
              <v-btn
                color="primary"
                outlined
                @click="showGlobalSearch = true"
              >
                <v-icon left>mdi-magnify</v-icon>
                Buscar Documentos
              </v-btn>
            </div>
          </div>

          <!-- Información del documento cuando está seleccionado -->
          <div v-else>
            <!-- Información principal -->
            <div class="document-info-section">
              <v-card-title class="text-h6 pa-4 pb-2">
                <v-icon class="mr-2">mdi-information</v-icon>
                Información General
              </v-card-title>
              
              <v-card-text class="pt-0">
                <div class="info-item mb-4">
                  <label class="info-label">Título</label>
                  <p class="info-value">{{ itemSelected.title }}</p>
                </div>
                
                <div class="info-item mb-4">
                  <label class="info-label">Descripción</label>
                  <p class="info-value">{{ itemSelected.description }}</p>
                </div>
              </v-card-text>
            </div>

          <!-- Metadatos -->
          <div class="metadata-section">
            <v-card-title class="text-h6 pa-4 pb-2">
              <v-icon class="mr-2">mdi-tag-multiple</v-icon>
              Metadatos
            </v-card-title>
            
            <v-card-text class="pt-0">
              <div class="metadata-grid">
                <div class="metadata-item">
                  <v-icon :color="getCategoryColor(itemSelected.category)" class="mr-2">
                    {{ getCategoryIcon(itemSelected.category) }}
                  </v-icon>
                  <div>
                    <div class="metadata-label">Categoría</div>
                    <v-chip 
                      :color="getCategoryColor(itemSelected.category)" 
                      small 
                      text-color="white"
                    >
                      {{ itemSelected.category }}
                    </v-chip>
                  </div>
                </div>

                <div class="metadata-item">
                  <v-icon color="blue" class="mr-2">mdi-account</v-icon>
                  <div>
                    <div class="metadata-label">Autor</div>
                    <v-chip color="blue" small text-color="white">
                      {{ itemSelected.author }}
                    </v-chip>
                  </div>
                </div>

                <div class="metadata-item">
                  <v-icon color="green" class="mr-2">mdi-calendar</v-icon>
                  <div>
                    <div class="metadata-label">Fecha de creación</div>
                    <v-chip color="green" small text-color="white">
                      {{ createdAdDoc }}
                    </v-chip>
                  </div>
                </div>

                <div class="metadata-item" v-if="itemSelected.ticket">
                  <v-icon color="orange" class="mr-2">mdi-ticket</v-icon>
                  <div>
                    <div class="metadata-label">Ticket relacionado</div>
                    <v-chip color="orange" small text-color="white">
                      {{ itemSelected.ticket }}
                    </v-chip>
                  </div>
                </div>
              </div>
            </v-card-text>
          </div>

          <!-- Acciones rápidas -->
          <div class="actions-section">
            <v-card-title class="text-h6 pa-4 pb-2">
              <v-icon class="mr-2">mdi-lightning-bolt</v-icon>
              Acciones Rápidas
            </v-card-title>
            
            <v-card-text class="pt-0">
              <v-row no-gutters>
                <v-col cols="12" class="pa-2">
                  <v-btn
                    color="primary"
                    block
                    @click="wacthpdf(itemSelected)"
                    class="action-button"
                  >
                    <v-icon left>mdi-eye</v-icon>
                    Ver Documento
                  </v-btn>
                </v-col>
                
                <v-col cols="12" class="pa-2" v-if="itemSelected.category === 'Boletines'">
                  <v-btn
                    color="purple"
                    block
                    @click="DownloadFromUrl(itemSelected)"
                    class="action-button"
                  >
                    <v-icon left>mdi-code-tags</v-icon>
                    Ver Código PHP
                  </v-btn>
                </v-col>
                
                <v-col cols="6" class="pa-2">
                  <v-btn
                    color="blue"
                    block
                    @click="editFileDialog()"
                    class="action-button"
                  >
                    <v-icon left>mdi-pencil</v-icon>
                    Editar
                  </v-btn>
                </v-col>
                
                <v-col cols="6" class="pa-2">
                  <v-btn
                    color="red"
                    block
                    @click="delFileDialog()"
                    class="action-button"
                  >
                    <v-icon left>mdi-delete</v-icon>
                    Eliminar
                  </v-btn>
                </v-col>
              </v-row>
            </v-card-text>
          </div>

          <!-- Estadísticas del documento -->
          <div class="stats-section">
            <v-card-title class="text-h6 pa-4 pb-2">
              <v-icon class="mr-2">mdi-chart-line</v-icon>
              Estadísticas
            </v-card-title>
            
            <v-card-text class="pt-0">
              <v-row>
                <v-col cols="6" class="text-center">
                  <div class="stat-number">{{ itemSelected.title ? itemSelected.title.length : 0 }}</div>
                  <div class="stat-label">Caracteres en título</div>
                </v-col>
                <v-col cols="6" class="text-center">
                  <div class="stat-number">{{ itemSelected.description ? itemSelected.description.length : 0 }}</div>
                  <div class="stat-label">Caracteres en descripción</div>
                </v-col>
              </v-row>
            </v-card-text>
          </div>
          </div>
        </v-card-text>
      </v-card>

      <!-- Estado colapsado -->
      <div v-else class="sidebar-right-collapsed-content">
        <div class="text-center pa-4">
          <v-icon size="32" color="primary">mdi-file-document-outline</v-icon>
          <p class="text-caption mt-2 text--secondary">Detalles</p>
          <v-divider class="my-2"></v-divider>
          <v-tooltip right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                v-bind="attrs"
                v-on="on"
                @click="miniRight = false"
                class="mt-2"
              >
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
            </template>
            <span>Expandir panel</span>
          </v-tooltip>
        </div>
      </div>
    </v-navigation-drawer>

    <v-main>
      <!--  -->
      <v-container>
        <v-row v-if="pdfsrc">
          <!-- <img :src="pdfsrc" alt=""> -->
          <iframe
            style="margin-top: 50px"
            :src="pdfsrc2"
            height="1200px"
            width="100%"
          ></iframe>
        </v-row>
        <v-row v-else>
          <welcome-home></welcome-home>
        </v-row>
      </v-container>
    </v-main>

    <!-- <v-footer app color="transparent" height="72" inset>
          <v-text-field background-color="grey lighten-1" dense flat hide-details rounded solo></v-text-field>
        </v-footer> -->
  </v-app>
</template>

<script>
import WelcomeHome from "./components/WelcomeHome";
//import AvatarLogin from "./components/AvatarLogin.vue";
import { config } from "../config/config";
//import data from "/public/data.json";
const usradm = config.userAdmin;
const usrpass = config.userPass;

import {
  storage,
  db,
  ref,
  uploadBytesResumable,
  getDownloadURL,
  collection,
  doc,
  addDoc,
  getDocs,
  deleteDoc,
  updateDoc,
  Timestamp,
} from "./firebase.js";

import { VueEditor } from "vue2-editor/dist/vue2-editor.core.js";

export default {
  data: () => ({
    user: {
      fullName: "Administrador",
      email: usradm,
    },
    chips: [],
    itemsCategory: ["Boletines", "Manuales"],
    mini: false,
    customToolbar: [["code-block"]],
    dialogDel: false,
    selectedItem: 0,
    progress: null,
    loading: false,
    dialog: false,
    snackbar: false,
    search: "",
    title: "",
    description: "",
    author: "",
    categoria: "",
    itemsCategoria: ["Boletines", "Manuales"],
    vertical: true,
    itemSelected: { title: "" },
    pdfsrc: null,
    pdfsrc2: null,
    timeout: 5000, //3seg
    drawer: null,
    pdfFile: "",
    phpFile: "",
    pdfList: [],
    snacktype: {
      color: "",
      icon: "",
      msg: "",
    },
    uploadValue: 0,
    rules: [(v) => v.length >= 10 || "Min 10 characters"],
    rulesDesc: [(v) => v.length <= 100 || "Max 100 characters"],
    wordsRules: [(v) => v.trim().split(" ").length <= 5 || "Max 5 words"],
    pdfListAll: [],
    filePDFData: null,
    filePHPData: null,
    dialogErr: false,
    dialogLogin: true,
    dialogEdit: false,
    email: "",
    password: "",
    showPassword: false,
    loginLoading: false,
    loginFormValid: false,
    rememberMe: false,
    emailError: "",
    passwordError: "",
    errorMessage: "El usuario o la contraseña no son correctos.",
    enabled: false,
    enabledPDF: false,
    enabledPHP: false,
    itemsAuthors: [
      "Alexis Bustamante",
      "Pablo Guiñazú",
      "Jesús Osorio",
      "Pablo Ormero",
    ],
    contentEditor: "",
    dialogShowphp: false,
    ticket: "",
    createdAdDoc: null,
    category: "",
    selectA: "",
    selectC: "",
    // Variables para la barra de navegación mejorada
    showGlobalSearch: false,
    globalSearchQuery: "",
    showNotifications: false,
    notificationCount: 0,
    // Variables para el sidebar mejorado
    miniSidebar: false,
    miniRight: false,
    // Variables para los diálogos mejorados
    formValid: false,
    // Reglas de validación para el login
    emailRules: [
      v => !!v || 'El correo electrónico es requerido',
      v => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser válido',
    ],
    passwordRules: [
      v => !!v || 'La contraseña es requerida',
      v => v.length >= 6 || 'La contraseña debe tener al menos 6 caracteres',
    ],
    // Reglas de validación para los formularios de documentos
    titleRules: [
      v => !!v || 'El título es requerido',
      v => v.length >= 5 || 'El título debe tener al menos 5 caracteres',
      v => v.length <= 100 || 'El título no puede exceder 100 caracteres',
    ],
    authorRules: [
      v => !!v || 'El autor es requerido',
    ],
    categoryRules: [
      v => !!v || 'La categoría es requerida',
    ],
    descriptionRules: [
      v => !!v || 'La descripción es requerida',
      v => v.length >= 10 || 'La descripción debe tener al menos 10 caracteres',
      v => v.length <= 500 || 'La descripción no puede exceder 500 caracteres',
    ],
    fileRules: [
      v => !!v || 'El archivo PDF es requerido',
    ],
  }),
  components: {
    WelcomeHome,
    VueEditor,
  },
  methods: {
    // Métodos para la barra de navegación mejorada
    toggleGlobalSearch() {
      this.showGlobalSearch = !this.showGlobalSearch;
      if (!this.showGlobalSearch) {
        this.globalSearchQuery = "";
      }
    },
    performGlobalSearch() {
      if (this.globalSearchQuery.trim()) {
        this.search = this.globalSearchQuery;
        this.toggleGlobalSearch();
      }
    },
    // Métodos para el sidebar mejorado
    getCategoryColor(category) {
      const colors = {
        'Boletines': 'blue',
        'Manuales': 'green',
        'Reportes': 'orange',
        'Otros': 'grey'
      };
      return colors[category] || 'grey';
    },
    getCategoryIcon(category) {
      const icons = {
        'Boletines': 'mdi-file-document-outline',
        'Manuales': 'mdi-book-open-outline',
        'Reportes': 'mdi-chart-line',
        'Otros': 'mdi-file-outline'
      };
      return icons[category] || 'mdi-file-outline';
    },
    // Métodos para el diálogo de código PHP
    copyToClipboard() {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(this.contentEditor).then(() => {
          this.snackbar = true;
          this.snacktype = {
            color: "success",
            icon: "mdi-check-circle-outline",
            msg: "Código copiado al portapapeles",
          };
        }).catch(() => {
          this.fallbackCopyToClipboard();
        });
      } else {
        this.fallbackCopyToClipboard();
      }
    },
    fallbackCopyToClipboard() {
      const textArea = document.createElement("textarea");
      textArea.value = this.contentEditor;
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      try {
        document.execCommand('copy');
        this.snackbar = true;
        this.snacktype = {
          color: "success",
          icon: "mdi-check-circle-outline",
          msg: "Código copiado al portapapeles",
        };
      } catch (err) {
        this.snackbar = true;
        this.snacktype = {
          color: "error",
          icon: "mdi-alert-circle-outline",
          msg: "No se pudo copiar el código",
        };
      }
      document.body.removeChild(textArea);
    },
    downloadCode() {
      const blob = new Blob([this.contentEditor], { type: 'text/php' });
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `${this.itemSelected.title || 'documento'}.php`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
      
      this.snackbar = true;
      this.snacktype = {
        color: "success",
        icon: "mdi-download",
        msg: "Archivo PHP descargado correctamente",
      };
    },
    remove(item) {
      this.chips.splice(this.chips.indexOf(item), 1);
    },
    changeStateA(valor) {
      this.author = "";
      this.author = valor;
      //console.log(this.categoria);
    },
    changeState(valorC) {
      this.categoria = "";
      this.categoria = valorC;
      this.enabledPHP = valorC == "Boletines" ? true : false;
      //console.log(this.categoria);
    },
    signOut() {
      this.dialogLogin = true;
    },
    cancelDialogEdit() {
      this.dialogEdit = false;
    },
    async editDocument() {
      if (this.$refs.formEditDocument.validate()) {
        this.loading = true;
        try {
          let itemEdit = {
            title: this.title,
            description: this.description,
            author: this.selectA,
            category: this.selectC,
            ticket: this.ticket,
          };

          const docRef = doc(db, "documents", this.itemSelected.id);

          if (this.enabledPDF && this.filePDFData) {
            itemEdit.pdfFile = await this.uploadTaskPromise(this.filePDFData);
          }

          await updateDoc(docRef, itemEdit);
          await this.getDocuments();
          this.itemSelected = { title: "" };
          this.loading = false;
          this.snackbar = true;
          this.dialogEdit = false;
          this.snacktype = {
            color: "success",
            icon: "mdi-check-circle-outline",
            msg: "Documento editado correctamente",
          };
        } catch (error) {
          console.log(error);
          this.loading = false;
        }
      }
    },
    editFileDialog() {
      //*opciones de la ventana

      this.enabledPDF = false;
      this.enabledPHP =
        this.itemSelected.category == "Boletines" ? true : false;

      this.filePDFData = null;
      this.filePHPData = null;
      //* son los elementos de la ventana
      this.title = this.itemSelected.title;
      this.description = this.itemSelected.description;
      this.ticket = this.itemSelected.ticket;
      this.category = this.itemSelected.category;
      this.selectA = this.itemSelected.author;
      this.selectC = this.itemSelected.category;
      this.contentEditor = this.itemSelected.phpFile;
      //*esto esta al momento de guardar
      this.author = this.itemSelected.author;
      this.category = this.itemSelected.category;

      //this.filePDFData = this.itemSelected.pdfFile;
      //this.filePHPData = this.itemSelected.phpFile;

      this.dialogEdit = true;
    },
    delFileDialog() {
      this.dialogDel = true;
    },
    async DeleteDoc() {
      //console.log(this.itemSelected);

      try {
        await deleteDoc(doc(db, "documents", this.itemSelected.id));
        //console.log("documento eliminado ", this.itemSelected);
        this.itemSelected = { title: "" };
        this.dialogDel = false;
        this.pdfsrc = "";
        await this.getDocuments(); //recargo lista.
      } catch (error) {
        console.log(error);
        this.dialogDel = false;
      }
    },
    async signIn() {
      if (this.$refs.loginForm.validate()) {
        this.loginLoading = true;
        this.dialogErr = false;
        
        // Simular un pequeño delay para mostrar el loading
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // TODO : esto es temporal, se valida con una variable de entorno.
        if (usradm == this.email && usrpass == this.password) {
          this.dialogLogin = false; //se logea "entrecomillas"
          this.snackbar = true;
          this.snacktype = {
            color: "success",
            icon: "mdi-check-circle-outline",
            msg: "Sesión iniciada correctamente",
          };
        } else {
          this.dialogErr = true;
        }
        
        this.loginLoading = false;
      }
    },
    assignDataPDF(event) {
      this.filePDFData = null;
      this.filePDFData = event[0];
      //this.filePDFData.name = this.getFileNameWithTime(this.filePDFData.name);
      console.log(this.filePDFData);
    },
    getFileExtension(filename) {
      //metodo que obtiene la extension del archivo
      return filename.slice(((filename.lastIndexOf(".") - 1) >>> 0) + 2);
    },
    getFileNameWithTime(fileName) {
      return (
        fileName.slice(0, fileName.lastIndexOf(".")) +
        new Date().getTime().toString() +
        ".pdf"
      );
    },
    async getDocuments() {
      this.pdfList = [];
      const querySnapshot = await getDocs(collection(db, "documents"));
      querySnapshot.forEach((doc) => {
        let newdoc = doc.data();
        newdoc.id = doc.id;
        this.pdfList.push(newdoc);
      });
    },
    async uploadTaskPromise(fileData) {
      return new Promise(function (resolve, reject) {
        const docRef = ref(
          storage,
          `documents/` +
            fileData.name.slice(0, fileData.name.lastIndexOf(".")) +
            new Date().getTime().toString() +
            ".pdf"
        );
        const uploadTask = uploadBytesResumable(docRef, fileData);

        uploadTask.on(
          "state_changed",
          (snapshot) => {
            // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
            //var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            //console.log("Upload is " + progress + "% done");
            switch (snapshot.state) {
              case "paused":
                //console.log("Upload is paused");
                break;
              case "running":
                //console.log("Upload is running");
                break;
            }
          },
          (error) => {
            // A full list of error codes is available at
            // https://firebase.google.com/docs/storage/web/handle-errors
            switch (error.code) {
              case "storage/unauthorized":
                // User doesn't have permission to access the object
                reject(error);
                break;
              case "storage/canceled":
                // User canceled the upload
                reject(error);
                break;

              // ...

              case "storage/unknown":
                // Unknown error occurred, inspect error.serverResponse
                reject(error);
                break;
            }
          },
          () => {
            //en el complete
            // Upload completed successfully, now we can get the download URL
            getDownloadURL(uploadTask.snapshot.ref).then((downloadURL) => {
              //console.log("File available at", downloadURL);
              resolve(downloadURL);
            });
          }
        );
      });
    },
    cancelDialog() {
      this.dialog = false;
      this.title = "";
      this.description = "";
      this.filePHPData = null;
      this.filePDFData = null;
    },
    assignDataPHP(event) {
      this.filePHPData = null;
      this.filePHPData = event[0];
      //console.log(this.filePHPData);
    },
    async SaveNewDoc() {
      let newDoc = {
        title: this.title,
        author: this.author,
        description: this.description,
        createdAd: Timestamp.fromDate(new Date()),
        category: this.categoria,
        ticket: this.ticket,
        pdfFile: "",
        fileName:
          `documents/` + this.getFileNameWithTime(this.filePDFData.name),
      };
      console.log(newDoc);
      newDoc.phpFile = this.categoria == "Boletines" ? this.contentEditor : "";

      //console.log(newDoc);
      //aca valido que todos los cmapos oblgatorios esten sino no se logra lamacenar nada.
      if (this.$refs.formNewDocument.validate()) {
        //subir el primer archivo pdf.
        this.loading = true;
        try {
          if (this.filePDFData) {
            newDoc.pdfFile = await this.uploadTaskPromise(this.filePDFData);
          }
          // una vez que los 2 archivos subieron
          //a firebase se crear el registro en la base de datos
          const docRef = await addDoc(collection(db, "documents"), newDoc);
          console.log("Document written with ID: ", docRef.id);
          this.loading = false;
          this.dialog = false;
          this.snackbar = true;
          this.snacktype = {
            color: "success",
            icon: "mdi-check-circle-outline",
            msg: "Documento almacenado correctamente",
          };
          await this.getDocuments();
        } catch (error) {
          console.log(error);
          this.loading = false;
          this.dialog = false;
          this.snackbar = true;
          this.snacktype = {
            color: "red accent-2",
            icon: "mdi-alert-circle-outline",
            msg: "No fue posible Almacenar documento.",
          };
        }
      }
    },

    wacthpdf(item) {
      this.pdfsrc = item.pdfFile;
      this.pdfsrc2 = item.pdfFile;
      this.itemSelected = item;
      this.category = this.itemSelected.category;
      this.contentEditor = item.phpFile;
      this.createdAdDoc = item.createdAd
        ? item.createdAd.toDate().toLocaleDateString()
        : "";

      //console.log(this.itemSelected);
    },
    addFileDialog() {
      this.title = "";
      this.description = "";
      this.phpFile = "";
      this.pdfFile = "";
      this.dialog = true;
      this.ticket = "";
      this.contentEditor = "";

      //this.contentEditor = item.phpFile;
    },
    DownloadFromUrl(item) {
      this.contentEditor = "";
      this.contentEditor = item.phpFile;
      this.dialogShowphp = true;
      // TODO: este codigo era el antiguo
      // var link = document.createElement("a");
      // link.href = item.phpFile;
      // link.download = item.title + ".php";
      // link.target = "_blank";
      // document.body.appendChild(link);

      // link.click();
      // document.body.removeChild(link);
    },
  },
  computed: {
    keywords() {
      if (!this.search) return [];
      const keywords = [];
      for (const search of this.searching) {
        keywords.push(search.keyword);
      }
      return keywords;
    },
    searching() {
      let arrayTemp = [];
      arrayTemp = this.pdfList;
      if (this.chips.length > 0) {
        //*primer filtro por Categoria
        arrayTemp = this.pdfList.filter((el) => {
          if (this.chips.indexOf(el.category) > -1) {
            return el;
          }
        });
      }
      if (!this.search) return arrayTemp;

      const search = this.search.toLowerCase();
      return arrayTemp.filter((item) => {
        const text = item.title.toLowerCase();
        return text.indexOf(search) > -1;
      });
    },
  },

  async created() {
    //aca debemos leer firestore
    await this.getDocuments();
    //console.log(this.pdfList);
    this.pdfsrc = "";
    //this.pdfList = data.datos;
    this.pdfListAll = this.pdfList;
  },
};
</script>

<style lang="css">
@import "~vue2-editor/dist/vue2-editor.css";

/*Import the Quill styles you want */
@import "~quill/dist/quill.core.css";
@import "~quill/dist/quill.bubble.css";
@import "~quill/dist/quill.snow.css";

/* Estilos para el login mejorado con esquema azul corporativo */
.login-container {
  background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
  position: relative;
  overflow: hidden;
}

.login-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="corporate-pattern" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="20" cy="40" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="40" cy="20" r="1" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23corporate-pattern)"/></svg>');
  opacity: 0.4;
}

.login-form-card {
  background: rgba(255, 255, 255, 0.98) !important;
  backdrop-filter: blur(20px);
  border-radius: 20px !important;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
  border: 1px solid rgba(255, 255, 255, 0.2);
  position: relative;
  z-index: 2;
}

.login-logo-container {
  position: relative;
  display: inline-block;
}

.login-logo-avatar {
  border: 4px solid #1976d2;
  box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
}

.login-logo-badge {
  position: absolute;
  bottom: -5px;
  right: -5px;
  background: #4caf50;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid white;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
}

.login-btn {
  background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%) !important;
  box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3) !important;
  transition: all 0.3s ease !important;
}

.login-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 12px 25px rgba(25, 118, 210, 0.4) !important;
}

.login-info-panel {
  background: linear-gradient(135deg, #0d47a1 0%, #1565c0 50%, #1976d2 100%);
  position: relative;
  overflow: hidden;
}

.login-bg-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="corporate-bg" width="80" height="80" patternUnits="userSpaceOnUse"><circle cx="40" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="60" cy="60" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="70" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="70" cy="10" r="1" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23corporate-bg)"/></svg>');
  opacity: 0.3;
}

.login-info-content {
  position: relative;
  z-index: 1;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
}

.login-main-logo {
  position: relative;
}

.features-card {
  background: rgba(255, 255, 255, 0.95) !important;
  backdrop-filter: blur(10px);
  border-radius: 16px !important;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: white;
  margin-bottom: 0.5rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.stat-label {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.8);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Mejoras generales para la aplicación */
.v-application {
  font-family: 'Roboto', sans-serif !important;
  background: #fafafa !important;
}

/* Variables CSS personalizadas - Esquema azul corporativo */
:root {
  --primary-gradient: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
  --secondary-gradient: linear-gradient(135deg, #424242 0%, #616161 100%);
  --success-gradient: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
  --warning-gradient: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
  --error-gradient: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
  --info-gradient: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
  
  --shadow-sm: 0 2px 4px rgba(25, 118, 210, 0.1);
  --shadow-md: 0 4px 8px rgba(25, 118, 210, 0.12);
  --shadow-lg: 0 8px 16px rgba(25, 118, 210, 0.15);
  --shadow-xl: 0 16px 32px rgba(25, 118, 210, 0.2);
  
  --border-radius-sm: 4px;
  --border-radius-md: 8px;
  --border-radius-lg: 12px;
  --border-radius-xl: 16px;
  --border-radius-2xl: 20px;
  
  --corporate-blue: #1976d2;
  --corporate-blue-dark: #1565c0;
  --corporate-blue-darker: #0d47a1;
  --corporate-blue-light: #42a5f5;
  --corporate-blue-lighter: #90caf9;
}

/* Tema oscuro */
.theme--dark {
  --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
  --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.4);
  --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.5);
  --shadow-xl: 0 16px 32px rgba(0, 0, 0, 0.6);
}

.theme--dark .v-application {
  background: #121212 !important;
}

.v-card {
  border-radius: 12px !important;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
}

.v-btn {
  text-transform: none !important;
  font-weight: 500 !important;
}

.v-text-field--outlined .v-input__control .v-input__slot {
  border-radius: 8px !important;
}

.v-navigation-drawer {
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1) !important;
}

/* Mejoras para la barra de navegación */
.v-app-bar {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
}

.app-bar-modern {
  background: var(--primary-gradient) !important;
  backdrop-filter: blur(10px);
}

.global-search-panel {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.global-search-field .v-input__control .v-input__slot {
  background: rgba(255, 255, 255, 0.9) !important;
  border-radius: 25px !important;
}

.theme-switch {
  margin: 0 !important;
}

.theme-switch .v-input__control {
  min-height: auto !important;
}

.theme-switch .v-input__slot {
  margin: 0 !important;
}

/* Estilos para el sidebar mejorado */
.sidebar-modern {
  background: #fafafa !important;
  border-right: 1px solid #e0e0e0 !important;
}

.sidebar-header {
  background: var(--primary-gradient) !important;
}

.search-panel {
  background: #ffffff !important;
  border-bottom: 1px solid #e0e0e0;
}

.action-buttons {
  background: #f5f5f5 !important;
  border-bottom: 1px solid #e0e0e0;
}

.action-btn {
  text-transform: none !important;
  font-size: 0.75rem !important;
}

.documents-list {
  background: #ffffff !important;
  flex: 1;
  overflow-y: auto;
}

.document-item {
  transition: all 0.3s ease !important;
  border-bottom: 1px solid #f0f0f0;
}

.document-item:hover {
  background-color: #f8f9fa !important;
}

.document-item--active {
  background-color: #e3f2fd !important;
  border-left: 4px solid #2196f3;
}

.document-item--active .v-list-item-title {
  color: #1976d2 !important;
  font-weight: 600 !important;
}

.sidebar-toggle {
  background: #f5f5f5 !important;
  border-top: 1px solid #e0e0e0;
}

/* Mejoras para el tema oscuro */
.theme--dark .sidebar-modern {
  background: #2d2d2d !important;
  border-right-color: #424242 !important;
}

.theme--dark .search-panel,
.theme--dark .documents-list {
  background: #2d2d2d !important;
}

.theme--dark .action-buttons {
  background: #3d3d3d !important;
}

.theme--dark .sidebar-toggle {
  background: #3d3d3d !important;
}

.theme--dark .document-item {
  border-bottom-color: #424242;
}

.theme--dark .document-item:hover {
  background-color: #3d3d3d !important;
}

.theme--dark .document-item--active {
  background-color: #1e3a5f !important;
}

/* Estilos para los diálogos mejorados */
.document-dialog .v-dialog {
  border-radius: 20px !important;
}

.dialog-card {
  border-radius: 20px !important;
  overflow: hidden;
}

.dialog-header {
  background: var(--primary-gradient) !important;
  color: white !important;
  padding: 1.5rem 2rem !important;
}

.dialog-content {
  padding: 2rem !important;
  max-height: 70vh;
  overflow-y: auto;
}

.dialog-actions {
  padding: 1rem 2rem !important;
  background: #f8f9fa;
  border-top: 1px solid #e0e0e0;
}

.form-section {
  background: #f8f9fa;
  border-radius: 12px !important;
  padding: 1.5rem;
  margin-bottom: 1rem;
}

.loading-container {
  padding: 4rem 2rem !important;
  min-height: 300px;
}

.php-editor {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  min-height: 300px;
}

/* Mejoras para el tema oscuro en diálogos */
.theme--dark .dialog-header {
  background: linear-gradient(135deg, #2d2d2d 0%, #424242 100%) !important;
}

.theme--dark .dialog-actions {
  background: #2d2d2d;
  border-top-color: #424242;
}

.theme--dark .form-section {
  background: #3d3d3d;
}

.theme--dark .php-editor {
  border-color: #424242;
  background: #2d2d2d;
}

/* Estilos para el sidebar derecho mejorado */
.sidebar-right-modern {
  background: #fafafa !important;
  border-left: 1px solid #e0e0e0 !important;
}

.sidebar-right-header {
  background: var(--primary-gradient) !important;
}

.sidebar-right-content {
  background: #ffffff !important;
  height: 100%;
  overflow-y: auto;
}

.document-info-section,
.metadata-section,
.actions-section,
.stats-section {
  border-bottom: 1px solid #f0f0f0;
}

.document-info-section:last-child,
.metadata-section:last-child,
.actions-section:last-child,
.stats-section:last-child {
  border-bottom: none;
}

.info-item {
  padding: 0.5rem 0;
}

.info-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #666;
  display: block;
  margin-bottom: 0.25rem;
}

.info-value {
  font-size: 0.9rem;
  color: #333;
  margin: 0;
  line-height: 1.4;
}

.metadata-grid {
  display: grid;
  gap: 1rem;
}

.metadata-item {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.metadata-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #666;
  margin-bottom: 0.25rem;
}

.action-button {
  text-transform: none !important;
  font-weight: 500 !important;
  border-radius: 8px !important;
}

.stats-section .stat-number {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2196f3;
  margin-bottom: 0.25rem;
}

.stats-section .stat-label {
  font-size: 0.75rem;
  color: #666;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Mejoras para el tema oscuro en sidebar derecho */
.theme--dark .sidebar-right-modern {
  background: #2d2d2d !important;
  border-left-color: #424242 !important;
}

.theme--dark .sidebar-right-content {
  background: #2d2d2d !important;
}

.theme--dark .document-info-section,
.theme--dark .metadata-section,
.theme--dark .actions-section,
.theme--dark .stats-section {
  border-bottom-color: #424242;
}

.theme--dark .info-label,
.theme--dark .metadata-label {
  color: #ccc;
}

.theme--dark .info-value {
  color: #fff;
}

.theme--dark .metadata-item {
  background: #3d3d3d;
  border-color: #424242;
}

.theme--dark .stats-section .stat-number {
  color: #64b5f6;
}

.theme--dark .stats-section .stat-label {
  color: #ccc;
}

/* Mejoras para los diálogos */
.v-dialog .v-card {
  border-radius: 16px !important;
}

/* Mejoras para los chips */
.v-chip {
  border-radius: 20px !important;
}

/* Animaciones suaves */
.v-list-item {
  transition: all 0.3s ease !important;
}

.v-list-item:hover {
  background-color: rgba(0, 0, 0, 0.04) !important;
}

/* Mejoras para el tema oscuro */
.theme--dark .login-form-card {
  background: rgba(30, 30, 30, 0.95) !important;
}

.theme--dark .v-card {
  background-color: #2d2d2d !important;
}

/* Responsive improvements */
@media (max-width: 960px) {
  .login-info-panel {
    display: none !important;
  }
  
  .login-form-card {
    margin: 1rem;
  }
  
  /* Sidebar responsivo */
  .sidebar-modern {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  .sidebar-right-modern {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  /* Diálogos responsivos */
  .document-dialog .v-dialog {
    margin: 1rem !important;
    max-width: calc(100vw - 2rem) !important;
  }
  
  .dialog-content {
    padding: 1rem !important;
    max-height: 60vh !important;
  }
  
  /* Formularios responsivos */
  .form-section {
    padding: 1rem !important;
  }
  
  /* Barra de navegación responsiva */
  .app-bar-modern .v-container {
    padding: 0 1rem !important;
  }
  
  .app-bar-modern .text-h6 {
    font-size: 1rem !important;
  }
  
  .app-bar-modern .text-caption {
    font-size: 0.7rem !important;
  }
}

@media (max-width: 600px) {
  /* Login móvil */
  .login-form-card {
    margin: 0.5rem;
    padding: 1rem !important;
  }
  
  /* Sidebar móvil */
  .sidebar-header .v-card-text {
    padding: 1rem !important;
  }
  
  .search-panel .v-card-text {
    padding: 1rem !important;
  }
  
  .action-buttons .v-card-text {
    padding: 0.5rem !important;
  }
  
  .action-btn {
    font-size: 0.7rem !important;
    padding: 0.25rem 0.5rem !important;
  }
  
  /* Documentos lista móvil */
  .document-item .v-list-item-content {
    padding: 0.5rem 0 !important;
  }
  
  .document-item .v-list-item-title {
    font-size: 0.9rem !important;
  }
  
  .document-item .v-list-item-subtitle {
    font-size: 0.8rem !important;
  }
  
  /* Sidebar derecho móvil */
  .sidebar-right-content .v-card-text {
    padding: 0.5rem !important;
  }
  
  .metadata-grid {
    gap: 0.5rem !important;
  }
  
  .metadata-item {
    padding: 0.5rem !important;
    flex-direction: column;
    text-align: center;
  }
  
  .metadata-item .v-icon {
    margin-bottom: 0.25rem !important;
    margin-right: 0 !important;
  }
  
  /* Diálogos móviles */
  .dialog-header {
    padding: 1rem !important;
  }
  
  .dialog-header .text-h5 {
    font-size: 1.1rem !important;
  }
  
  .dialog-actions {
    padding: 0.5rem 1rem !important;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .dialog-actions .v-btn {
    width: 100% !important;
    margin: 0 !important;
  }
  
  /* Barra de navegación móvil */
  .app-bar-modern .v-row {
    flex-wrap: nowrap !important;
  }
  
  .app-bar-modern .v-col {
    padding: 0.25rem !important;
  }
  
  .app-bar-modern .v-btn {
    min-width: 40px !important;
    width: 40px !important;
    height: 40px !important;
  }
  
  /* Ocultar elementos en móvil */
  .app-bar-modern .d-none.d-md-flex {
    display: none !important;
  }
}

@media (max-width: 400px) {
  /* Pantallas muy pequeñas */
  .login-form-card {
    margin: 0.25rem;
    padding: 0.75rem !important;
  }
  
  .sidebar-modern,
  .sidebar-right-modern {
    width: 100vw !important;
  }
  
  .document-dialog .v-dialog {
    margin: 0.5rem !important;
    max-width: calc(100vw - 1rem) !important;
  }
  
  .dialog-content {
    padding: 0.75rem !important;
  }
  
  .form-section {
    padding: 0.75rem !important;
  }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Mejoras para pantallas de alta densidad */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .v-icon {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
}

/* Estilos para diálogos mejorados */
.document-dialog .v-dialog__content,
.delete-dialog .v-dialog__content,
.code-dialog .v-dialog__content {
  border-radius: 20px !important;
}

.dialog-card,
.delete-dialog-card,
.code-dialog-card {
  border-radius: 20px !important;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.dialog-header,
.delete-dialog-header,
.code-dialog-header {
  background: var(--primary-gradient) !important;
  color: white !important;
  padding: 1.5rem 2rem !important;
}

.dialog-content,
.delete-dialog-content,
.code-dialog-content {
  padding: 2rem !important;
}

.dialog-actions,
.delete-dialog-actions,
.code-dialog-actions {
  background: #f8f9fa;
  border-top: 1px solid #e0e0e0;
  padding: 1rem 2rem !important;
}

.loading-container {
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-section {
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 1.5rem;
  background: #fafafa;
}

.file-section {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 1rem;
  background: white;
}

.php-editor {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  min-height: 400px;
}

.document-preview {
  border-radius: 12px !important;
  border: 1px solid #e0e0e0;
}

.code-editor-container {
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  overflow: hidden;
  background: white;
}

.code-editor-header {
  background: #f5f5f5;
  padding: 0.5rem 1rem;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  align-items: center;
}

.code-editor-wrapper {
  min-height: 400px;
}

.code-editor {
  min-height: 400px !important;
}

.code-dialog-info {
  background: #f8f9fa;
  border-bottom: 1px solid #e0e0e0;
}

/* Animaciones para diálogos */
.document-dialog .v-dialog__content,
.delete-dialog .v-dialog__content,
.code-dialog .v-dialog__content {
  animation: dialogSlideIn 0.3s ease-out;
}

@keyframes dialogSlideIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Responsive para diálogos */
@media (max-width: 960px) {
  .dialog-content,
  .delete-dialog-content,
  .code-dialog-content {
    padding: 1rem !important;
  }
  
  .dialog-actions,
  .delete-dialog-actions,
  .code-dialog-actions {
    padding: 1rem !important;
    flex-direction: column;
  }
  
  .dialog-actions .v-btn,
  .delete-dialog-actions .v-btn,
  .code-dialog-actions .v-btn {
    width: 100%;
    margin: 0.25rem 0;
  }
  
  .form-section {
    padding: 1rem;
  }
  
  .php-editor,
  .code-editor {
    min-height: 300px !important;
  }
}

@media (max-width: 600px) {
  .dialog-header,
  .delete-dialog-header,
  .code-dialog-header {
    padding: 1rem !important;
  }
  
  .dialog-header h2,
  .delete-dialog-header h2,
  .code-dialog-header h2 {
    font-size: 1.1rem !important;
  }
  
  .dialog-content,
  .delete-dialog-content,
  .code-dialog-content {
    padding: 0.75rem !important;
  }
  
  .form-section {
    padding: 0.75rem;
  }
  
  .php-editor,
  .code-editor {
    min-height: 250px !important;
  }
}

/* Estilos para el sidebar derecho colapsado */
.sidebar-right-collapsed {
  width: 80px !important;
}

.sidebar-right-collapsed-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: #f8f9fa;
}

.sidebar-right-collapsed-content .v-icon {
  transition: all 0.3s ease;
}

.sidebar-right-collapsed-content .v-btn:hover .v-icon {
  transform: scale(1.1);
}

/* Estado vacío del sidebar */
.empty-state {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.empty-state .v-icon {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 1;
  }
}

/* Mejoras para el sidebar derecho */
.sidebar-right-modern {
  transition: width 0.3s ease !important;
  border-left: 1px solid #e0e0e0;
}

.sidebar-right-modern.sidebar-right-collapsed {
  border-left: 1px solid #e0e0e0;
}

/* Tema oscuro para estados colapsados */
.theme--dark .sidebar-right-collapsed-content {
  background: #2d2d2d;
}

.theme--dark .empty-state {
  background: linear-gradient(135deg, #2d2d2d 0%, #1e1e1e 100%);
}

.theme--dark .sidebar-right-modern {
  border-left-color: #424242;
}
</style>