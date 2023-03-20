<template>
  <v-app id="inspire">
    <!-- <v-system-bar :color="'#11334d'" app>
      <v-spacer></v-spacer>
      <v-icon>mdi-square</v-icon>
      <v-icon>mdi-circle</v-icon>
      <v-icon>mdi-triangle</v-icon>
    </v-system-bar> -->
    <!-- DIALOG EDITAR -->
    <v-row justify="center">
      <v-dialog v-model="dialogEdit" persistent max-width="600px">
        <v-card>
          <v-toolbar v-show="loading ? false : true" color="#11334d">
            <span class="text-h5" style="color: white">Editar Documento</span>
          </v-toolbar>

          <v-card-text>
            <v-container v-if="loading ? true : false">
              <v-row
                class="fill-height"
                align-content="center"
                justify="center"
              >
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
              <v-form ref="formEditDocument">
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

                <v-row align="center"> </v-row>
                <v-row>
                  <v-col cols="10">
                    <v-file-input
                      :disabled="!enabledPDF"
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
                    ></v-file-input>
                  </v-col>
                  <v-col>
                    <v-checkbox
                      v-model="enabledPDF"
                      hide-details
                      class="shrink mr-2 mt-0"
                    ></v-checkbox>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="10">
                    <v-file-input
                      :disabled="!enabledPHP"
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
                    ></v-file-input>
                  </v-col>
                  <v-col>
                    <v-checkbox
                      v-model="enabledPHP"
                      hide-details
                      class="shrink mr-2 mt-0"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-form>
              <small>*indicates required field</small>
            </v-container>
          </v-card-text>

          <v-card-actions v-if="loading ? false : true">
            <v-spacer></v-spacer>
            <v-btn
              color="red darken-1"
              text
              @click.prevent="cancelDialogEdit()"
            >
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click.prevent="editDocument()">
              Edit
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- DIALOG ELIMINA DOC-->
    <v-dialog
      v-model="dialogDel"
      transition="dialog-top-transition"
      max-width="600"
    >
      <v-card>
        <!-- <v-toolbar color="primary" dark> Eliminar Registro</v-toolbar> -->
        <v-card-title>
          ¿Desea eliminar el registro{{ itemSelected.title }}?</v-card-title
        >
        <v-card-text>
          <p class="text--secondary">
            Esta acción es irreversible, por favor asegúrese de que el registro
            sea el correcto a eliminar.
          </p>
        </v-card-text>
        <v-card-actions class="justify-end">
          <v-btn color="primary" text @click="dialogDel = false">Close</v-btn>
          <v-btn color="red" text @click="DeleteDoc">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- LOGIN -->
    <v-dialog
      v-model="dialogLogin"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar color="#11334d"> </v-toolbar>
        <v-divider></v-divider>
        <v-row>
          <v-col cols="12" md="6">
            <v-card-text class="mt-12">
              <v-row align="center" justify="center">
                <img
                  style="height: 300px; margin-bottom: 20px"
                  src="./assets/logo_app.png"
                  alt=""
                />
              </v-row>
              <h4 class="text-center"></h4>
              <v-row align="center" justify="center">
                <v-col cols="12" sm="8">
                  <v-text-field
                    label="Email"
                    outlined
                    dense
                    color="blue"
                    autocomplete="false"
                    class="mt-16"
                    v-model="email"
                  />
                  <v-text-field
                    label="Password"
                    outlined
                    dense
                    color="blue"
                    autocomplete="false"
                    type="password"
                    v-model="password"
                  />
                  <v-row>
                    <v-col cols="12" sm="7">
                      <!-- <v-checkbox label="Remember Me" class="mt-n1" color="blue"> </v-checkbox> -->
                    </v-col>
                    <v-col cols="12" sm="5">
                      <!-- <span class="caption blue--text">Forgot password</span> -->
                    </v-col>
                  </v-row>
                  <v-btn color="blue" dark @click="signIn()" block tile
                    >Log in</v-btn
                  >
                  <v-alert
                    style="margin-top: 10px"
                    dismissible
                    v-model="dialogErr"
                    type="error"
                    outlined
                  >
                    El usuario o la contraseña no son correctas.
                  </v-alert>
                  <!-- <h5 class="text-center  grey--text mt-4 mb-3">Or Log in using</h5> -->
                  <div></div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-col>
          <v-col cols="12" md="6" class="primary rounded-bl-xl">
            <div style="height=2300px;text-align: center; padding: 180px 0;">
              <v-card-text class="white--text">
                <v-row align="center" justify="center">
                  <img
                    style="height: 300px; margin-bottom: 10px"
                    src="./assets/logo.png"
                    alt=""
                  />
                </v-row>
                <h1 class="text-center">Repositorio de Informes</h1>
                <h5 class="text-center">Gracias por contribuir con nostros.</h5>
              </v-card-text>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-dialog>

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
              <v-row
                class="fill-height"
                align-content="center"
                justify="center"
              >
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
            <v-btn color="red darken-1" text @click.prevent="cancelDialog()">
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click.prevent="SaveNewDoc()">
              Save
            </v-btn>
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
      <v-navigation-drawer
        v-model="drawer"
        absolute
        color="#11334d"
        mini-variant
      >
        <!-- <v-avatar
          class="d-block text-center mx-auto mt-4"
          color="grey darken-1"
          size="36"
        >
        </v-avatar> -->
        <v-container fluid>
          <v-row justify="center">
            <v-menu bottom min-width="200px" rounded offset-y>
              <template v-slot:activator="{ on }">
                <v-btn style="margin-top: 5px" icon x-large v-on="on">
                  <v-avatar size="48">
                    <v-icon color="white"> mdi-account-circle </v-icon>
                  </v-avatar>
                </v-btn>
              </template>
              <v-card>
                <v-list-item-content class="justify-center">
                  <div class="mx-auto text-center">
                    <v-avatar size="48">
                      <v-icon color="black"> mdi-account-circle </v-icon>
                    </v-avatar>
                    <h3>{{ user.fullName }}</h3>
                    <p class="text-caption mt-1">
                      {{ user.email }}
                    </p>
                    <v-divider class="my-3"></v-divider>
                    <v-btn depressed rounded text @click="signOut()">
                      Cerrar Sesión
                    </v-btn>
                  </div>
                </v-list-item-content>
              </v-card>
            </v-menu>
          </v-row>
        </v-container>

        <!-- <v-icon dark class="d-block text-center mx-auto mt-4"
          >mdi-file-chart</v-icon
        > -->

        <v-divider class="mx-3 my-5"></v-divider>
        <v-icon
          @click="addFileDialog()"
          dark
          class="d-block text-center mx-auto mt-4"
          >mdi-file-plus</v-icon
        >
        <v-icon
          @click="delFileDialog()"
          dark
          :disabled="itemSelected.title != '' ? false : true"
          class="d-block text-center mx-auto mt-4"
          >mdi-file-document-remove</v-icon
        >
        <v-icon
          @click="editFileDialog()"
          dark
          :disabled="itemSelected.title != '' ? false : true"
          class="d-block text-center mx-auto mt-4"
          >mdi-file-document-edit</v-icon
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
} from "./firebase.js";

export default {
  data: () => ({
    user: {
      fullName: "Administrador",
      email: usradm,
    },
    dialogDel: false,
    selectedItem: 0,
    progress: null,
    loading: false,
    dialog: false,
    snackbar: false,
    search: "",
    title: "",
    description: "",
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
    enabled: false,
    enabledPDF: false,
    enabledPHP: false,
  }),
  components: {
    WelcomeHome,
  },
  methods: {
    signOut() {
      this.dialogLogin = true;
    },
    cancelDialogEdit() {
      this.dialogEdit = false;
    },
    async editDocument() {
      this.loading = true;

      if (this.$refs.formEditDocument.validate()) {
        try {
          let itemEdit = {
            title: this.title,
            description: this.description,
          };

          const docRef = doc(db, "documents", this.itemSelected.id);

          if (this.enabledPDF && this.filePDFData) {
            itemEdit.pdfFile = await this.uploadTaskPromise(this.filePDFData);
          }

          if (this.enabledPHP && this.filePHPData) {
            itemEdit.phpFile = await this.uploadTaskPromise(this.filePHPData);
          }

          await updateDoc(docRef, itemEdit);
          await this.getDocuments();
          this.itemSelected = {};
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
      this.enabledPDF = false;
      this.enabledPHP = false;
      this.filePDFData = null;
      this.filePHPData = null;

      this.title = this.itemSelected.title;
      this.description = this.itemSelected.description;

      //this.filePDFData = this.itemSelected.pdfFile;
      //this.filePHPData = this.itemSelected.phpFile;

      this.dialogEdit = true;
    },
    delFileDialog() {
      this.dialogDel = true;
    },
    async DeleteDoc() {
      console.log(this.itemSelected);

      try {
        await deleteDoc(doc(db, "documents", this.itemSelected.id));
        console.log("documento eliminado ", this.itemSelected);
        this.itemSelected = {};
        this.dialogDel = false;
        await this.getDocuments(); //recargo lista.
      } catch (error) {
        console.log(error);
        this.dialogDel = false;
      }
    },
    signIn() {
      // this.email=""
      // this.password=""
      // TODO : esto es temporal, se valida con una variable de entorno.
      //
      if (usradm == this.email && usrpass == this.password) {
        this.dialogLogin = false; //se logea "entrecomillas"
      } else {
        this.dialogErr = true;
      }
    },
    getFileExtension(filename) {
      //metodo que obtiene la extension del archivo
      return filename.slice(((filename.lastIndexOf(".") - 1) >>> 0) + 2);
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
    cancelDialog() {
      this.dialog = false;
      this.title = "";
      this.description = "";
      this.filePHPData = null;
      this.filePDFData = null;
    },
    assignDataPDF(event) {
      this.filePDFData = null;
      this.filePDFData = event[0];
      //console.log(this.filePDFData);
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
        //subir el primer archivo pdf.
        this.loading = true;
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
    this.pdfListAll = this.pdfList;
  },
};
</script>
