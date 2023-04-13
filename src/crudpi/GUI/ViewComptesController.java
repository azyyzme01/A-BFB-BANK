/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.GUI;

import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.collections.transformation.SortedList;
import javafx.event.ActionEvent;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableCell;
import javafx.scene.control.TableColumn;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.util.Callback;
import crudpi.entities.comptesBancaire;
import crudpi.services.compteBancaireCRUD;
import java.io.IOException;
import static java.nio.file.Files.delete;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.TableView;
/**
 * FXML Controller class
 *
 * @author ANAS
 */
public class ViewComptesController implements Initializable {

    @FXML
    private TableView<comptesBancaire> tableview;
    @FXML
    private TableColumn<comptesBancaire, Integer> col1;
    @FXML
    private TableColumn<comptesBancaire, String> col2;
    @FXML
    private TableColumn<comptesBancaire, String> col3;
    @FXML
    private TableColumn<comptesBancaire, Integer> col5;
    @FXML
    private TableColumn<comptesBancaire, Float> col6;
    @FXML
    private TableColumn<comptesBancaire, String> col4;
    @FXML
    private Button addBtn;
    @FXML
    private TableColumn<comptesBancaire, Void> delete;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
         List<comptesBancaire> comptesBancaireList = compteBancaireCRUD.getAll();

    // Add data to the table
    ObservableList<comptesBancaire> data = FXCollections.observableArrayList(comptesBancaireList);
    col1.setCellValueFactory(new PropertyValueFactory<comptesBancaire, Integer>("id")); 
    col2.setCellValueFactory(new PropertyValueFactory<comptesBancaire, String>("nom"));
    col3.setCellValueFactory(new PropertyValueFactory<comptesBancaire, String>("prenom"));
    col4.setCellValueFactory(new PropertyValueFactory<comptesBancaire, String>("email"));
    col5.setCellValueFactory(new PropertyValueFactory<comptesBancaire, Integer>("num_tlfn"));
    col6.setCellValueFactory(new PropertyValueFactory<comptesBancaire, Float>("solde_initial"));
    tableview.setItems(data);

    
     Callback<TableColumn<comptesBancaire, Void>, TableCell<comptesBancaire, Void>> cellFactory = new Callback<TableColumn<comptesBancaire, Void>, TableCell<comptesBancaire, Void>>() {
            @Override
            public TableCell<comptesBancaire, Void> call(final TableColumn<comptesBancaire, Void> param) {
                final TableCell<comptesBancaire, Void> cell = new TableCell<comptesBancaire, Void>() {
                    private final Button btn = new Button("Supprimer");
                    {
                        btn.setOnAction((ActionEvent event) -> {
                            int rowIndex = getTableRow().getIndex();
                            compteBancaireCRUD pcd = new compteBancaireCRUD();
                            pcd.deleteEntity(col1.getCellObservableValue(rowIndex).getValue());

                        });
                    }                    
                    @Override
                    public void updateItem(Void item, boolean empty) {
                        super.updateItem(item, empty);
                        if (empty) {
                            setGraphic(null);
                        } else {
                            setGraphic(btn);
                        }
                    }
                };
                return cell;
            }
        };
       delete.setCellFactory(cellFactory);
        
        
}

    @FXML
         public void showAdd(){
         FXMLLoader loader = new FXMLLoader(getClass().getResource("ajouter_comptebancaire.fxml"));
       try{
       Parent root = loader.load(); 

        addBtn.getScene().setRoot(root);
       
       }catch(IOException ex){
       
        System.out.println(ex.getMessage());
    }
   }
         
      
    }    
    

