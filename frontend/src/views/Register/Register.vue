<template>
    <div class="register_account">
        <div class="wrap">
            <h4 class="title">Créer un compte</h4>
            <form id="formulaire" class="login100-form validate-form"  v-on:submit.prevent="onSubmit">
                <div class="col_1_of_2 span_1_of_2">
                    <div>
                        <select v-model="crudData.type_client" id="type_client" name="type_client" class="frm-field required">
                            <option value="" disabled> Choisissez Un type</option>
                            <option value="Personnel">Personnel</option>
                            <option value="Société">Société</option>
                        </select>
                    </div>
                    <div v-show="crudData.type_client==='Personnel'">
                        <input type="text" name="nom_client" class="text-uppercase" v-model="crudData.nom_client" placeholder="Nom">
                    </div>
                    <div v-show="crudData.type_client==='Personnel'">
                        <input type="text" name="prenom_client" class="text-capitalize" v-model="crudData.prenom_client" placeholder="Prénom(s)">
                    </div>
                    <div v-show="crudData.type_client==='Société'">
                        <input type="text" name="raison_sociale_client" v-model="crudData.raison_sociale_client" placeholder="Raison social">
                    </div>
                    <div v-show="crudData.type_client==='Société'">
                        <input type="text" name="nif_client" v-model="crudData.nif_client" placeholder="NIF">
                    </div>
                    <div v-show="crudData.type_client==='Société'">
                        <input type="text" name="stat_client" v-model="crudData.stat_client" placeholder="STAT">
                    </div>
                    <div>
                        <input required id="mdp" type="password" name="mdp_client" v-model="crudData.mdp_client" placeholder="mots de passe">
                    </div>
                    <div>
                        <input required id="mdp1" type="password" name="mdp_client_confirmation" v-model="crudData.mdp_client_confirmation" placeholder="confirmation du mots de passe">
                    </div>
                </div>
                <div class="col_1_of_2 span_1_of_2">
                    <div>
                        <input type="text"  name="login_client" v-model="crudData.login_client" placeholder="Login">
                    </div>
                    <div>
                        <input type="text"  name="mail_client" v-model="crudData.mail_client" placeholder="E-mail">
                    </div>
                    <div>
                        <input type="text"  name="telephone_client" v-model="crudData.telephone_client" placeholder="Telephone">
                    </div>
                    <div>
                        <input type="text" name="adresse_client" v-model="crudData.adresse_client" placeholder="Adresse">
                    </div>
                    <div>
                        <input type="text" disabled style="border: none!important;" class="bg-white">
                    </div>
                    <div>
                        <input type="text" disabled style="border: none!important;" class="bg-white">
                    </div>
                    <div>
                        <input type="text" disabled style="border: none!important;" class="bg-white">
                    </div>
                    <div>

                    </div>
                </div>
                <button type="submit" class="grey">S'enregister</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</template>

<script src="./Register.js"></script>

<style scoped>
    #mdp, #mdp1{

        font-size: 0.8125em!important;
        color: #999!important;
        padding: 8px!important;
        outline: none!important;
        margin: 10px 0!important;
        width: 95%!important;
        font-family: 'Open Sans', sans-serif!important;
        border: 1px solid #f0f0f0!important;
    }
</style>
