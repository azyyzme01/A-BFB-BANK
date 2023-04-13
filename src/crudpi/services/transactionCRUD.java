/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.services;


import crudpi.entities.comptesBancaire;
import crudpi.entities.Transaction;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import crudpi.interfaces.entityCRUD;
import crudpi.utils.connexion;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author ANAS
 */
public class transactionCRUD implements entityCRUD<Transaction>{
    
      @Override
    public void addEntity(Transaction t) {
        try {
    int compte_source_id =t.getCompte_source_id();
    String nom = t.getNom();
    String prenom = t.getPrenom();
    String email = t.getEmail();
    int num_tlfn= t.getNum_tlfn();
    int compte_destination = t.getCompte_destination();
    float montant = t.getMontant();
    

    String insertReservationQuery = "INSERT INTO transaction (compte_source_id,nom, prenom, email, num_tlfn,compte_destination, montant) VALUES (?,?,?, ?, ?, ?, ?)";
    PreparedStatement stmp = connexion.getInstance().getCnx().prepareStatement(insertReservationQuery);
        
    stmp.setInt(1, compte_source_id);
    stmp.setString(2, nom);
    stmp.setString(3, prenom);
    stmp.setString(4, email);
    stmp.setInt(5, num_tlfn);
    stmp.setInt(6, compte_destination);
    stmp.setFloat(7, montant);
//    stmp.executeUpdate();
  //          System.out.println("ajout successsss");
 

    int rowsAffected = stmp.executeUpdate();

    if (rowsAffected > 0) {
        System.out.println("comptes added successfully");
    } else {
        System.out.println("Failed to add compte");
    }
} catch (SQLException ex) {
    System.out.println(ex.getMessage());
}
    }

    @Override
    public List<Transaction> entitiesList() {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
    
    
         public static List<Transaction> getAll() {
    List<Transaction> TransactionList = new ArrayList<>();
    String requete = "SELECT * FROM transaction" ;
    
    try (
            PreparedStatement statement=connexion.getInstance()
                .getCnx().prepareStatement(requete);
         ResultSet resultSet = statement.executeQuery()) {
        while (resultSet.next()) {
            Transaction transaction = new Transaction();
            transaction.setId(resultSet.getInt("id"));
            transaction.setNom(resultSet.getString("nom"));
            transaction.setPrenom(resultSet.getString("prenom"));
            transaction.setEmail(resultSet.getString("email"));
            transaction.setNum_tlfn(resultSet.getInt("num_tlfn"));
            transaction.setSolde_initial(resultSet.getFloat("solde_initial"));

            TransactionList.add(transaction);
        }
    } catch (SQLException e) {
        e.printStackTrace();
    }
    return comptesBancaireList;
       }
    
}
