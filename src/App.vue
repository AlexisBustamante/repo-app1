<template>
  <v-app id="inspire">
    <!-- <v-system-bar :color="'#11334d'" app>
      <v-spacer></v-spacer>
      <v-icon>mdi-square</v-icon>
      <v-icon>mdi-circle</v-icon>
      <v-icon>mdi-triangle</v-icon>
    </v-system-bar> -->
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
          <v-toolbar color="#11334d">
            <span class="text-h5" style="color: white">Upload new Template</span>
          </v-toolbar>
          <v-card-text>
            <v-container>
              <v-form ref="formNewDocument">
                <!-- <v-text-field
                  outlined
                  v-model="title"
                  label="Title"
                  :rules="rules"
                  required
                ></v-text-field>
                <v-textarea
                  outlined
                  v-model="description"
                  label="Description"
                  required
                ></v-textarea> -->
                <v-row>
                  <v-col cols="10">
                    <v-file-input
                      prepend-icon="mdi-file-pdf-box"
                      outlined
                      chips
                      multiple
                      color="deep-purple accent-4"
                      counter
                      dense
                      show-size
                      accept=".pdf"
                      label="attach pdf file"
                      @change="assignDataPDF"
                      :rules="[(v) => !!v || 'File is mandatory']"
                    ></v-file-input>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="10">
                    <v-file-input
                      prepend-icon="mdi-language-php"
                      outlined
                      dense
                      chips
                      show-size
                      multiple
                      counter
                      accept=".php"
                      label="attach PHP file"
                      @change="assignDataPHP"
                      :rules="[(v) => !!v || 'File is mandatory']"
                    ></v-file-input>
                  </v-col>
                </v-row>
              </v-form>
            </v-container>
            <small>*indicates required field</small>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" text @click.prevent="dialog = false">
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click.prevent="SaveNewDoc()"> Save </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <v-app-bar color="#11334d" dark app clipped-right flat height="72">
      <v-app-bar-title>
        {{ pdfsrc != "" ? itemSelected.title : "" }}
      </v-app-bar-title>
      <v-spacer></v-spacer>

      <v-responsive max-width="90">
        <v-btn class="mx-7" color="#11334d" fab @click="pdfsrc = ''">
          <v-icon> mdi-help-circle-outline </v-icon>
        </v-btn>
        <!-- <v-text-field dense flat hide-details rounded solo-inverted></v-text-field> -->
      </v-responsive>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app width="400">
      <v-navigation-drawer v-model="drawer" absolute color="#11334d" mini-variant>
        <!-- <v-avatar class="d-block text-center mx-auto mt-4" color="grey darken-1" size="36">
    
                </v-avatar> -->
        <v-icon dark class="d-block text-center mx-auto mt-4">mdi-file-chart</v-icon>

        <v-divider class="mx-3 my-5"></v-divider>
        <v-icon @click="addFileDialog()" dark class="d-block text-center mx-auto mt-4"
          >mdi-file-plus</v-icon
        >
        <!-- <v-avatar v-for="n in 6" :key="n" class="d-block text-center mx-auto mb-9" color="grey lighten-1"
              size="28"></v-avatar> -->
      </v-navigation-drawer>

      <v-sheet class="pl-16 pa-3" color="#11334d" height="72" width="100%">
        <v-text-field
          dark
          v-model="search"
          append-icon="mdi-magnify"
          label="Search for title"
          dense
          flat
          hide-details
          rounded
          solo-inverted
        ></v-text-field>
      </v-sheet>

      <v-list-item
        class="pl-16"
        three-line
        v-for="item in searching"
        :key="item.id"
        link
        v-on:click="wacthpdf(item)"
      >
        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
          <v-list-item-subtitle>
            {{ item.description }}
          </v-list-item-subtitle>
          <v-list-item-subtitle class="pa-1">
            <a class="pa-2" :href="item.urlScriptPHP" :download="item.fileName">
              <v-btn rounded color="primary" dark>
                <v-icon>mdi-language-php</v-icon>
              </v-btn>
            </a>
            <!-- 
                        <a style="text-decoration: none;" :href=item.urlExamplePDF :download="item.filePDF">
                            <v-btn rounded color="error" dark>
                                <v-icon>mdi-file-pdf-box</v-icon>
                            </v-btn>
                        </a> -->
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
          <!-- <img :src="pdfsrc" alt=""> -->

          <iframe :src="pdfsrc2" height="1200px" width="100%"></iframe>
        </v-row>
        <v-row v-else>
          <v-timeline align-top :dense="$vuetify.breakpoint.smAndDown">
            <v-timeline-item
              v-for="(item, i) in items"
              :key="i"
              :color="item.color"
              :icon="item.icon"
              fill-dot
            >
              <v-card :color="item.color" dark>
                <v-card-title class="text-h6">
                  {{ item.title }}
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
import data from "/public/data.json";
import { storage, ref, uploadBytesResumable, getDownloadURL } from "./firebase.js";
import {config} from '../config/config.js'
console.log(config);

export default {
  data: () => ({
    progress: null,
    search: "",
    title: "",
    description: "",
    dialog: true,
    itemSelected: {},
    selectedPdf: "./BoletinInglesSecundaria.pdf",
    pdfsrc: null,
    pdfsrc2: null,
    loading4: false,
    drawer: null,
    pdfFile: "",
    phpFile: "",
    pdfList: [],
    uploadValue: 0,
    rules: [(v) => v.length >= 20 || "Min 20 characters"],
    rulesDesc: [(v) => v.length <= 100 || "Max 30 characters"],
    wordsRules: [(v) => v.trim().split(" ").length <= 5 || "Max 5 words"],
    pdfListAll: [],
    items: [
      {
        color: "indigo",
        icon: "mdi-magnify",
        title: "Busca un informe",
        description:
          "Busca un ejemplo del informe que tienes que desarrollar a travez del nombre o titulo del mismo.",
      },
      {
        title: "Visualiza un ejemplo",
        color: "green lighten-1",
        icon: "mdi-eye-check-outline",
        description:
          "selecciona el informe que buscaste para poder ver un ejemplo del informe, así sabrás graficamente si es lo que necesitas. ",
      },
      {
        color: "purple darken-1",
        icon: "mdi-book-variant",
        title: "Descarga el Script",
        description:
          "Descarga todo el codigo fuente del ejemplo el cual  te permitirá crear  el informe con una base de HTML,CSS y PHP sin tener que empezar de 0",
      },
    ],
    filePDFData: null,
    filePHPData:null
  }),
  methods: {
    getFileExtension(filename) {
      //metodo que obtiene la extension del archivo
      return filename.slice(((filename.lastIndexOf(".") - 1) >>> 0) + 2);
    },

    async uploadTaskPromise(fileData) {
      return new Promise(function (resolve, reject) {
        const docRef = ref(storage, `documents/` + fileData.name);
        const uploadTask = uploadBytesResumable(docRef, fileData);

        uploadTask.on(
          "state_changed",
          (snapshot) => {
            // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
            var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;

            console.log("Upload is " + progress + "% done");
            switch (snapshot.state) {
              case "paused":
                console.log("Upload is paused");
                break;
              case "running":
                console.log("Upload is running");
                break;
            }
          },
          (error) => {
            // A full list of error codes is available at
            // https://firebase.google.com/docs/storage/web/handle-errors
            switch (error.code) {
              case "storage/unauthorized":
                // User doesn't have permission to access the object
                reject();
                break;
              case "storage/canceled":
                // User canceled the upload
                reject();
                break;

              // ...

              case "storage/unknown":
                // Unknown error occurred, inspect error.serverResponse
                reject();
                break;
            }
          },
          () => {
            //en el complete
            // Upload completed successfully, now we can get the download URL
            getDownloadURL(uploadTask.snapshot.ref).then((downloadURL) => {
              console.log("File available at", downloadURL);
              resolve(downloadURL);
            });
          }
        );
      });
    },
    assignDataPDF(event) {
      this.filePDFData = null;
      this.filePDFData = event[0];
      console.log(this.filePDFData);
    },
    assignDataPHP(event) {
      this.filePHPData = null;
      this.filePHPData = event[0];
      console.log(this.filePHPData);
    },

    async SaveNewDoc() {
      let newDoc = {
        title: this.title,
        description: this.description,
        phpFile: "",
        pdfFile: "",
      };

      //aca valido que todos los cmapos oblgatorios esten sino no se logra lamacenar nada.
      if (this.$refs.formNewDocument.validate()) {
        console.log(newDoc);
        //subir el primer archivo pdf.
        try {
          // const urlWebPdf = await this.uploadFile(this.filePDFData);

          //se suben los archivos
          newDoc.pdfFile = await this.uploadTaskPromise(this.filePDFData);
          newDoc.phpFile = await this.uploadTaskPromise(this.filePHPData);
          
          // una vez que los 2 archivos subieron 
          //a firebase se crear el registro en la base de datos
          console.log(newDoc);

        } catch (error) {
          console.log(error);
        }
      }
    },
    wacthpdf(item) {
      this.pdfsrc = item.urlExample;
      this.pdfsrc2 = item.urlExamplePDF;
      this.itemSelected = item;
    },
    addFileDialog() {
      this.title = "";
      this.description = "";
      this.phpFile = "";
      this.pdfFile = "";
      this.dialog = true;
    },
  },
  components: {},
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
      if (!this.search) return this.pdfList;
      const search = this.search.toLowerCase();
      return this.pdfList.filter((item) => {
        const text = item.title.toLowerCase();
        return text.indexOf(search) > -1;
      });
    },
  },
  created() {
    this.pdfsrc = "";
    this.pdfList = data.datos;
    this.pdfListAll = data.datos;
  },
};
</script>
