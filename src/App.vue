<template>
<v-app id="inspire">
    <v-system-bar :color="'#11334d'" app>
        <v-spacer></v-spacer>
        <v-icon>mdi-square</v-icon>
        <v-icon>mdi-circle</v-icon>
        <v-icon>mdi-triangle</v-icon>
    </v-system-bar>

    <v-app-bar color="#94b8d7" dark app clipped-right flat height="72">
        <v-app-bar-title>
            {{ pdfsrc != "" ? itemSelected.title:''}}
        </v-app-bar-title>
        <v-spacer></v-spacer>

        <v-responsive max-width="90">
            <v-btn class="mx-7" color="#11334d" fab @click="pdfsrc=''">
                <v-icon>
                    mdi-help-circle-outline
                </v-icon>
            </v-btn>
            <!-- <v-text-field dense flat hide-details rounded solo-inverted></v-text-field> -->
        </v-responsive>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app width="400">
        <v-navigation-drawer v-model="drawer" absolute color="#215971" mini-variant>
            <!-- <v-avatar class="d-block text-center mx-auto mt-4" color="grey darken-1" size="36">

            </v-avatar> -->
            <v-icon dark class="d-block text-center mx-auto mt-4">mdi-file-chart</v-icon>

            <v-divider class="mx-3 my-5"></v-divider>

            <!-- <v-avatar v-for="n in 6" :key="n" class="d-block text-center mx-auto mb-9" color="grey lighten-1"
          size="28"></v-avatar> -->
        </v-navigation-drawer>

        <v-sheet class="pl-16 pa-3" color="#94b8d7" height="72" width="100%">
            <v-text-field dark v-model="search" append-icon="mdi-magnify" label="Search for title" dense flat hide-details rounded solo-inverted></v-text-field>
        </v-sheet>

        <v-list-item class="pl-16" three-line v-for="item in searching" :key="item.id" link v-on:click="wacthpdf(item)">
            <v-list-item-content>
                <v-list-item-title>{{ item.title }}</v-list-item-title>
                <v-list-item-subtitle>
                    {{item.description}}
                </v-list-item-subtitle>
                <v-list-item-subtitle class="pa-1">
                    <a class="pa-2" :href=item.urlScriptPHP :download="item.fileName">
                        <v-btn rounded color="primary" dark>
                            
                            <v-icon>mdi-language-php</v-icon>
                        </v-btn>
                    </a>

                    <a style="text-decoration: none;" :href=item.urlExamplePDF :download="item.filePDF">
                        <v-btn rounded color="error" dark>
                            <v-icon>mdi-file-pdf-box</v-icon>
                        </v-btn>
                    </a>
                </v-list-item-subtitle>

            </v-list-item-content>
        </v-list-item>

        <!-- //esta es la lista original -->
        <!-- <v-list class="pl-14" shaped>
        <v-list-item v-for="item in pdfList" :key="item.id" link>
          <v-list-item-content>
            <v-list-item-title> {{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list> -->
    </v-navigation-drawer>

    <!-- <v-navigation-drawer app clipped right>
      <v-list lines="three">
        <v-list-item v-for="n in 5" :key="n" link>
          <v-list-item-content>
            <v-list-item-title>botones de descarga {{ n }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer> -->

    <v-main>
        <!--  -->
        <v-container>
            <v-row v-if="pdfsrc">
                <img :src="pdfsrc" alt="">
            </v-row>
            <v-row v-else>

                <v-timeline align-top :dense="$vuetify.breakpoint.smAndDown">
                    <v-timeline-item v-for="(item, i) in items" :key="i" :color="item.color" :icon="item.icon" fill-dot>
                        <v-card :color="item.color" dark>
                            <v-card-title class="text-h6">
                                {{item.title}}
                            </v-card-title>
                            <v-card-text class="white text--primary">
                                <p>
                                    {{ item.description }}
                                </p>
                            </v-card-text>
                        </v-card>
                    </v-timeline-item>
                </v-timeline>
            </v-row>
        </v-container>
    </v-main>

    <!-- <v-footer app color="transparent" height="72" inset>
      <v-text-field background-color="grey lighten-1" dense flat hide-details rounded solo></v-text-field>
    </v-footer> -->
</v-app>
</template>

<script>
import data from '/public/data.json'

export default {
    data: () => ({
        search: '',
        itemSelected: {},
        selectedPdf: './BoletinInglesSecundaria.pdf',
        pdfsrc: null,
        loading4: false,
        drawer: null,
        pdfList: [],
        pdfListAll: [],
        items: [{
                color: 'indigo',
                icon: 'mdi-magnify',
                title: 'Busca un informe',
                description: 'Busca un ejemplo del informe que tienes que desarrollar a travez del nombre o titulo del mismo.'
            },
            {
                title: 'Visualiza un ejemplo',
                color: 'green lighten-1',
                icon: 'mdi-eye-check-outline',
                description: 'selecciona el informe que buscaste para poder ver un ejemplo del informe, así sabrás graficamente si es lo que necesitas. '

            },
            {
                color: 'purple darken-1',
                icon: 'mdi-book-variant',
                title: 'Descarga el Script',
                description: 'Descarga todo el codigo fuente del ejemplo el cual  te permitirá crear  el informe con una base de HTML,CSS y PHP sin tener que empezar de 0'

            },

        ],
    }),
    methods: {
        wacthpdf(item) {
            this.pdfsrc = item.urlExample;
            this.itemSelected = item
        },

    },
    components: {},
    computed: {
        keywords() {
            if (!this.search) return []
            const keywords = []
            for (const search of this.searching) {
                keywords.push(search.keyword)
            }
            return keywords
        },
        searching() {
            if (!this.search) return this.pdfList
            const search = this.search.toLowerCase()
            return this.pdfList.filter(item => {
                const text = item.title.toLowerCase()
                return text.indexOf(search) > -1
            })
        }
    },
    created() {
        this.pdfsrc = ''
        this.pdfList = data.datos
        this.pdfListAll = data.datos
    }
}
</script>
