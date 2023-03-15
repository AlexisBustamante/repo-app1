<template>
  <v-app id="inspire">
    <!-- <v-system-bar :color="'#11334d'" app>
      <v-spacer></v-spacer>
      <v-icon>mdi-square</v-icon>
      <v-icon>mdi-circle</v-icon>
      <v-icon>mdi-triangle</v-icon>
    </v-system-bar> -->

    <!-- FORMULARIO DE INGRESO QUE PUEDE SER UN COMPONENTE. -->
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
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
          <v-toolbar v-show="loading ? false : true" color="#11334d">
            <span class="text-h5" style="color: white">Nuevo Documento</span>
          </v-toolbar>

          <v-card-text>
            <v-container v-if="loading ? true : false">
              <v-row class="fill-height" align-content="center" justify="center">
                <v-col class="text-subtitle-1 text-center" cols="12">
                  Subiendo Archivos a la Nube ...
                </v-col>
                <v-col class="text-subtitle-1 text-center" cols="12">
                  <v-icon x-large>mdi-cloud-arrow-up-outline</v-icon>
                </v-col>

                <v-col cols="6">
                  <v-progress-linear
                    color="primary"
                    indeterminate
                    rounded
                    height="6"
                  ></v-progress-linear>
                </v-col>
              </v-row>
            </v-container>
            <v-container v-if="loading ? false : true">
              <v-form ref="formNewDocument">
                <v-text-field
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
                ></v-textarea>
                <v-row>
                  <v-col cols="12">
                    <v-file-input
                      prepend-icon="mdi-file-pdf-box"
                      outlined
                      chips
                      multiple
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
                  <v-col cols="12">
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
              <small>*indicates required field</small>
            </v-container>
          </v-card-text>

          <v-card-actions v-if="loading ? false : true">
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" text @click.prevent="dialog = false">
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click.prevent="SaveNewDoc()"> Save </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <v-app-bar color="#11334d" dark app clipped-right flat height="60">
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

      <v-sheet class="pl-16 pa-3" color="#11334d" height="60" width="100%">
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

      <v-list-item-group v-model="selectedItem" color="primary">
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
              <v-icon color="primary" x-large @click="DownloadFromUrl(item)"
                >mdi-language-php</v-icon
              >
              <!-- 
                        <a style="text-decoration: none;" :href=item.urlExamplePDF :download="item.filePDF">
                            <v-btn rounded color="error" dark>
                                <v-icon>mdi-file-pdf-box</v-icon>
                            </v-btn>
                        </a> -->
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>

      <!-- //esta es la lista original -->
      <!-- <v-list class="pl-14" shaped>
            <v-list-item v-for="item in pdfList" :key="item.id" link>
              <v-list-item-content>
                <v-list-item-title> {{ item.title }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list> -->
    </v-navigation-drawer>

    <!--LA COLUMNA DERECHA <v-navigation-drawer app clipped right>
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
 //import data from "/public/data.json";
import {
  storage,
  db,
  ref,
  uploadBytesResumable,
  getDownloadURL,
  collection,
  addDoc,
  getDocs,
} from "./firebase.js";

export default {
  data: () => ({
    selectedItem: 0,
    progress: null,
    loading: false,
    dialog: false,
    snackbar: false,
    search: "",
    title: "",
    description: "",
    vertical: true,
    itemSelected: {},
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
  }),
  components: {
    WelcomeHome,
  },
  methods: {
    getFileExtension(filename) {
      //metodo que obtiene la extension del archivo
      return filename.slice(((filename.lastIndexOf(".") - 1) >>> 0) + 2);
    },
    async getDocuments() {
      this.pdfList = [];
      const querySnapshot = await getDocs(collection(db, "documents"));
      querySnapshot.forEach((doc) => {
          let newdoc = doc.data()
          newdoc.id = doc.id
        this.pdfList.push(newdoc);
      });
    },
    async uploadTaskPromise(fileData) {
      return new Promise(function (resolve, reject) {
        const docRef = ref(storage, `documents/` + fileData.name);
        const uploadTask = uploadBytesResumable(docRef, fileData);

        uploadTask.on(
          "state_changed",
          (snapshot) => {
            // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
            //var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            //console.log("Upload is " + progress + "% done");
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
      this.loading = true;
      let newDoc = {
        title: this.title,
        description: this.description,
        phpFile: "",
        pdfFile: "",
      };

      //aca valido que todos los cmapos oblgatorios esten sino no se logra lamacenar nada.
      if (this.$refs.formNewDocument.validate()) {
        //subir el primer archivo pdf.
        try {
          newDoc.pdfFile = await this.uploadTaskPromise(this.filePDFData);
          newDoc.phpFile = await this.uploadTaskPromise(this.filePHPData);
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
      //console.log(item);
    },
    addFileDialog() {
      this.title = "";
      this.description = "";
      this.phpFile = "";
      this.pdfFile = "";
      this.dialog = true;
    },
    DownloadFromUrl(item) {
      var link = document.createElement("a");
      link.href = item.phpFile;
      link.download = item.title + ".php";
      link.target = "_blank";
      document.body.appendChild(link);

      link.click();
      document.body.removeChild(link);
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
      if (!this.search) return this.pdfList;
      const search = this.search.toLowerCase();
      return this.pdfList.filter((item) => {
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
    //this.pdfListAll = this.pdfList;
  },
};
</script>
