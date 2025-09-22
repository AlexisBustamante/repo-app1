import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
  theme: {
    dark: false,
    themes: {
      light: {
        primary: '#1976d2',
        secondary: '#424242',
        accent: '#82b1ff',
        error: '#f44336',
        warning: '#ff9800',
        info: '#2196f3',
        success: '#4caf50',
        background: '#f5f5f5',
        surface: '#ffffff',
        'on-primary': '#ffffff',
        'on-secondary': '#ffffff',
        'on-surface': '#212121',
        'on-background': '#212121',
      },
      dark: {
        primary: '#2196f3',
        secondary: '#616161',
        accent: '#82b1ff',
        error: '#f44336',
        warning: '#ff9800',
        info: '#2196f3',
        success: '#4caf50',
        background: '#121212',
        surface: '#1e1e1e',
        'on-primary': '#ffffff',
        'on-secondary': '#ffffff',
        'on-surface': '#ffffff',
        'on-background': '#ffffff',
      }
    }
  },
  options: {
    customProperties: true
  },
  defaults: {
    VBtn: {
      textTransform: 'none',
      fontWeight: '500',
      borderRadius: '8px'
    },
    VCard: {
      elevation: 2,
      borderRadius: '12px'
    },
    VTextField: {
      outlined: true,
      dense: true
    },
    VSelect: {
      outlined: true,
      dense: true
    },
    VTextarea: {
      outlined: true,
      dense: true
    },
    VFileInput: {
      outlined: true,
      dense: true
    },
    VChip: {
      borderRadius: '20px'
    },
    VDialog: {
      borderRadius: '16px'
    }
  }
});
