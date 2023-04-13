/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.GUI;

import crudpi.entities.comptesBancaire;
import crudpi.services.compteBancaireCRUD;
import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;

/**
 * FXML Controller class
 *
 * @author ANAS
 */
public class Ajouter_comptebancaireController implements Initializable {

    @FXML
    private TextField tfnom ;
    @FXML
    private TextField tfprenom;
    @FXML
    private TextField tfemail;
    @FXML
    private TextField tfnum_tlfn;
    @FXML
    private TextField tfsolde_initial;
    @FXML
    private Button bntajoutercompte;
    @FXML
    private Button btnmodifier_compte;
    // @FXML
    //private Button bntsupprimer_compte;
    @FXML
    private TextField idf;
    @FXML
    private Button deleteBtn;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }    
     private boolean isFieldNotEmpty(String field) {
    return field != null && !field.isEmpty();
    }
    
    private boolean validateForm() {
         String nom = tfnom.getText();
        String prenom= tfprenom.getText();
        String email = tfemail.getText();
        int num_tlfn = Integer.parseInt(tfnum_tlfn.getText());
        float solde_initial = Float.valueOf(tfsolde_initial.getText());
        
        
        if  (!isFieldNotEmpty(nom) || !isFieldNotEmpty(prenom) || !isFieldNotEmpty(email))
                 {
            
            Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Validate Form");
            alert.setHeaderText(null);
            alert.setContentText("All fields must be filled!");
            alert.showAndWait();
            return false;
        }
        return true;
        
        
    }

    @FXML
         public void showComptes(){
         FXMLLoader loader = new FXMLLoader(getClass().getResource("viewComptes.fxml"));
       try{
       Parent root = loader.load(); 

        bntajoutercompte.getScene().setRoot(root);
       
       }catch(IOException ex){
       
        System.out.println(ex.getMessage());
    }
   }
    
    @FXML
    private void ajoutercompte(ActionEvent event) {
        if(validateForm()){
            String nom = tfnom.getText();
        String prenom= tfprenom.getText();
        String email = tfemail.getText();
        int num_tlfn = Integer.parseInt(tfnum_tlfn.getText());
        float solde_initial = Float.valueOf(tfsolde_initial.getText());
         if(solde_initial<0f || num_tlfn<0){
           Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Validate Form");
            alert.setHeaderText(null);
            alert.setContentText("solde_initial or num_tlfn invalid");
            alert.showAndWait(); 
        }
        else{
        comptesBancaire compte = new comptesBancaire(nom,prenom,email,num_tlfn,solde_initial);
        System.out.println(compte);
        compteBancaireCRUD cb = new compteBancaireCRUD();
        
        cb.addEntity(compte);
        showComptes();
        }
        }
        else{
            System.out.println("Invalid champ");
        }
         
    }
     @FXML
    public void modifier_compte(ActionEvent event){
        
        int id = Integer.parseInt(idf.getText());
       String nom = tfnom.getText();
        String prenom= tfprenom.getText();
        String email = tfemail.getText();
        int num_tlfn = Integer.parseInt(tfnum_tlfn.getText());
        float solde_initial = Float.valueOf(tfsolde_initial.getText());
       
         if(solde_initial<0f || num_tlfn<0){
           Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Validate Form");
            alert.setHeaderText(null);
            alert.setContentText("solde_initial or num_tlfn invalid");
            alert.showAndWait(); 
        }
        else{
        comptesBancaire compte = new comptesBancaire(nom,prenom,email,num_tlfn,solde_initial);
        compteBancaireCRUD cb = new compteBancaireCRUD();
        cb.updateEntity(id,nom,prenom,email,num_tlfn,solde_initial);
        }
        
    }

    @FXML
    private void deleteEntity(ActionEvent event) {
        int idCompte = Integer.valueOf(idf.getText());
        compteBancaireCRUD cb = new compteBancaireCRUD();
        cb.deleteEntity(idCompte);
    }
}
