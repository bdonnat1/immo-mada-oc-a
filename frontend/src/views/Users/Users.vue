<style scoped>
/* @import "~vue-toast-notification/dist/theme-sugar.css";
@import "~vue-select/dist/vue-select.css"; */
</style>
<template>
  <div class="">
    <div class="row" style="padding-bottom: 5px">
      <div class="col-lg-6">
        <div class="input-group">
          <select
            class="form-control form-control-sm"
            style="max-width: 125px"
            v-model="criteriacol"
          >
            <option value="" selected>Filtrer par ...</option>
            <option value="nom">Nom complet</option>
            <option value="login">Login</option>
            <option value="role">Role</option>
            <option value="statut">Statut</option>
          </select>
          <input
            type="text"
            class="form-control form-control-sm"
            @keyup.enter="setFilter()"
            v-model="motCle"
            placeholder="Rechercher ..."
          />
          <div class="input-group-append" id="button-addon4">
            <button
              class="btn btn-outline-secondary btn-sm"
              type="button"
              @click="setFilter()"
            >
              <i class="fas fa-search"></i> Go
            </button>
            <button
              class="btn btn-outline-secondary btn-sm"
              type="button"
              @click="resetFilter()"
            >
              <i class="fas fa-times"></i> Init
            </button>
          </div>
        </div>
      </div>
      <div class="col-lg-6 text-right">
        <button type="button" class="btn btn-sm btn-dark" @click="tester()" v-if="false">
          Tester
        </button>
        <button class="btn btn-secondary btn-sm" @click="addRow" type="button"  >
          <i class="fas fa-plus"></i> Nouveau
        </button>
        &nbsp;
        <button
          @click="onExport"
          class="btn btn-secondary btn-sm"
          type="button"
        >
          <i class="fas fa-file-excel"></i> Exporter
        </button>
        &nbsp;
        <!-- <button class="btn btn-danger btn-sm" disabled type="button">
          <i class="fas fa-times"></i> supprimer
        </button> -->
        <download-excel
          class="btn btn-default d-none"
          id="excel-download"
          :data="exportexcel.json_data"
          :fields="exportexcel.json_fields"
          :worksheet="NOM_SOCIETE"
          :name="NOM_SOCIETE+'-' + pageTitle + '.xls'"
        >
          Download Excel (you can customize this with html code!)
        </download-excel>
      </div>
    </div>

  <div style="overflow-x: auto">
    <vuetable
      class="table-scroll text-nowrap table-responsive  w-100 d-block d-md-table table table-striped table-bordered table-hovered"
      ref="vuetable"
      :api-url="BASE_URL+'/users/fetchall'"
      :fields="vuetable.fields"
      :sort-order="vuetable.sortOrder"
      :css="vuetable.css.table"
      pagination-path=""
      data-path="data"
      :per-page="20"
      @vuetable:pagination-data="onPaginationData"
      @vuetable:loading="onLoading"
      @vuetable:loaded="onLoaded"
      :append-params="vuetable.moreParams"
      track-by="item_code"
    >
      <template slot="actions" scope="props">
        <div class="table-button-container">
          <button
            class="btn btn-outline-secondary btn-sm"
            @click="editRow(props.rowData)"
          >
            <span class="fas fa-pencil-alt"></span></button
          >&nbsp;
          <button
            class="btn btn-outline-danger btn-sm"
            @click="deleteRow(props.rowData)"
          >
            <span class="fas fa-trash"></span></button
          >&nbsp;
          <!-- <div class="btn-group">
            <button
              class="btn btn-outline-secondary btn-sm dropdown-toggle"
              type="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              &nbsp;
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <router-link
                :to="{ path: '/transfert/employee/' + props.rowData.job_id }"
                class="dropdown-item"
              >
                <i class="fas fa-exchange-alt"></i>
                <span> &nbsp;Transfer order to abroad</span>
              </router-link>
              <div class="dropdown-divider"></div>
              <router-link to="/employee" class="dropdown-item">
                <i class="fas fa-university"></i>
                <span> &nbsp;List of employee banks</span>
              </router-link>
            </div>
          </div> -->
        </div>
      </template>
    </vuetable>
    </div>
    <vuetable-pagination
      ref="pagination"
      :css="vuetable.css.pagination"
      @vuetable-pagination:change-page="onChangePage"
    ></vuetable-pagination>

    <b-modal
      id="crudmodal"
      :title="crudmodaltitle"
      size="lg"
      hide-footer
      scrollable
      centered
    >
      <form id="formulaire" v-on:submit.prevent="onSubmit">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="nom">Nom Complet</label>
              <input
                type="text"
                v-model="crudform.nom"
                class="form-control"
                id="nom"
                placeholder="Nom complet de l'utilisateur"
                name="nom"
                required
              />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="login">Login</label>
              <input
                type="text"
                v-model="crudform.login"
                class="form-control"
                id="login"
                placeholder="Nom d'utilisateur"
                name="login"
                required
              />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="mdp">Mot de passe</label>
              <input
                type="password"
                v-model="crudform.mdp"
                class="form-control"
                id="mdp"
                placeholder="******"
                name="mdp"
                @input="isValidPassword"
              />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="mdp">Validation du mot de passe</label>
              <input
                type="password"
                v-model="crudform.validation_mdp"
                class="form-control"
                id="validation_mdp"
                placeholder="******"
                name="validation_mdp"
                @input="isValidPassword"
              />
            </div>
          </div>
          <div class="col-md-12 text-center" v-if="error_password != ''">
            <div class="alert alert-danger" role="alert">
              {{ error_password }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="role">Role</label>
              <select
                type="text"
                v-model="crudform.role"
                class="form-control"
                id="role"
                name="role"
                required
              >
                <option value="ADMIN">Administrateur</option>
                <option value="MAGASINIER">Magasinier</option>
                <option value="COMPTABLE">Comptable</option>
                <option value="LOGISTIQUE">Logistique</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="statut">Statut du compte</label>
              <select
                type="text"
                v-model="crudform.statut"
                class="form-control"
                id="statut"
                name="statut"
                required
              >
                <option value="ACTIVE">Active</option>
                <option value="DESACTIVE">Désactivé</option>
              </select>
            </div>
          </div>
          
        </div>

        <input
          type="hidden"
          v-model="crudform.id"
          id="id"
          placeholder="id"
          name="id"
        />

        <div class="modal-footer">
          <b-button variant="secondary" @click="closeModal()">Fermer</b-button>
          <b-button type="submit" variant="primary">Enregsitrer</b-button>
        </div>
      </form>
    </b-modal>
  </div>
</template>
<!--/users/fetchall-->
<script src="./Users.js"></script>
