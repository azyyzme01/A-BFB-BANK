/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.GUI;

import crudpi.entities.*;
import crudpi.services.*;
import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableCell;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.util.Callback;

/**
 * FXML Controller class
 *
 * @author ANAS
 */
public class ViewTransactionsController implements Initializable {

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
   @FXML
    private TableView<Transaction> tableview;
    @FXML
    private TableColumn<Transaction, Integer> col1;
    @FXML
    private TableColumn<Transaction, String> col2;
    @FXML
    private TableColumn<Transaction, String> col3;
    @FXML
    private TableColumn<Transaction, Integer> col5;
    @FXML
    private TableColumn<Transaction, Float> col6;
    @FXML
    private TableColumn<Transaction, String> col4;
    @FXML
    private Button addBtn;

   
         List<comptesBancaire> comptesBancaireList = compteBancaireCRUD.getAll();

    // Add data to the table
    ObservableList<comptesBancaire> data = FXCollections.observableArrayList(comptesBancaireList);
    col1.setCellValueFactory(new PropertyValueFactory<Transaction, Integer>("id")); 
    col2.setCellValueFactory(new PropertyValueFactory<Transaction, String>("nom"));
    col3.setCellValueFactory(new PropertyValueFactory<Transaction, String>("prenom"));
    col4.setCellValueFactory(new PropertyValueFactory<Transaction, String>("email"));
    col5.setCellValueFactory(new PropertyValueFactory<Transaction, Integer>("num_tlfn"));
    col6.setCellValueFactory(new PropertyValueFactory<Transaction, Float>("solde_initial"));
    tableview.setItems(data);

    
     Callback<TableColumn<Transaction, Void>, TableCell<Transaction, Void>> cellFactory = new Callback<TableColumn<Transaction, Void>, TableCell<Transaction, Void>>() {
            @Override
            public TableCell<Transaction, Void> call(final TableColumn<Transaction, Void> param) {
                final TableCell<Transaction, Void> cell = new TableCell<Transaction, Void>() {
                /*    private final Button btn = new Button("Supprimer");
                    {
                        btn.setOnAction((ActionEvent event) -> {
                            int rowIndex = getTableRow().getIndex();
                            transactionCRUD pcd = new transactionCRUD();
                            pcd.deleteEntity(col1.getCellObservableValue(rowIndex).getValue());

                        });
                    } */                   
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
       // delete.setCellFactory(cellFactory);
        
        
 
    }    
    }    
