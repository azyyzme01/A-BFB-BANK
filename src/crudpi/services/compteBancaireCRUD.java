/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.services;

import crudpi.entities.comptesBancaire;
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
public class compteBancaireCRUD implements entityCRUD<comptesBancaire> {
    
    @Override
    public void addEntity(comptesBancaire r) {
        try {
    String nom = r.getNom();
    String prenom = r.getPrenom();
    String email = r.getEmail();
    int num_tlfn = r.getNum_tlfn();
    float solde = r.getSolde_initial();
    

    String insertReservationQuery = "INSERT INTO comptebancaire (nom, prenom, email, num_tlfn, solde_initial) VALUES (?, ?, ?, ?, ?)";
    PreparedStatement stmp = connexion.getInstance().getCnx().prepareStatement(insertReservationQuery);
    stmp.setString(1, nom);
    stmp.setString(2, prenom);
    stmp.setString(3, email);
    stmp.setInt(4, num_tlfn);
    stmp.setFloat(5, solde);
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
    public List<comptesBancaire> entitiesList() {
         ArrayList<comptesBancaire> myList = new ArrayList();
        
        try {
            String requete = "SELECT * FROM comptebancaire";
            Statement st = connexion.getInstance().getCnx().createStatement();
            ResultSet rs = st.executeQuery(requete);
            
            while(rs.next()){
                comptesBancaire cb =new comptesBancaire();
                
                cb.setId(rs.getInt(1));
                cb.setNom(rs.getString("nom"));
                cb.setPrenom(rs.getString("prenom"));
                cb.setEmail(rs.getString("email"));
                cb.setNum_tlfn(rs.getInt(5));
                cb.setSolde_initial(rs.getFloat(6));
               
                                
                myList.add(cb);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }       
        return myList;
        //throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
    
     public void deleteEntity(int id) {
        
        try {
            String requete="DELETE FROM comptebancaire WHERE id=?";
            PreparedStatement st=connexion.getInstance().getCnx().prepareStatement(requete);
            
            st.setInt(1,id);
            

            st.executeUpdate();
            System.out.println("comptebancaire deleted!");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
     }
     
      public void updateEntity(int id,String nom,String prenom,String email,int num_tlfn,float solde_intial){
        
         try{
        String requete = "UPDATE comptebancaire set nom = ?,prenom = ?,email = ?,num_tlfn  = ?,solde_initial=? WHERE id = ?";
        PreparedStatement st=connexion.getInstance()
                .getCnx().prepareStatement(requete);
            
              st.setString(1, nom);
              st.setString(2, prenom);
              st.setString(3, email);
              st.setInt(4, num_tlfn);
              st.setFloat(5, solde_intial);
                      st.setInt(6, id);

        st.executeUpdate();
        }catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        }
      
       public static List<comptesBancaire> getAll() {
    List<comptesBancaire> comptesBancaireList = new ArrayList<>();
    String requete = "SELECT * FROM comptebancaire" ;
    
    try (
            PreparedStatement statement=connexion.getInstance()
                .getCnx().prepareStatement(requete);
         ResultSet resultSet = statement.executeQuery()) {
        while (resultSet.next()) {
            comptesBancaire comptesBancaire = new comptesBancaire();
            comptesBancaire.setId(resultSet.getInt("id"));
            comptesBancaire.setNom(resultSet.getString("nom"));
            comptesBancaire.setPrenom(resultSet.getString("prenom"));
            comptesBancaire.setEmail(resultSet.getString("email"));
            comptesBancaire.setNum_tlfn(resultSet.getInt("num_tlfn"));
            comptesBancaire.setSolde_initial(resultSet.getFloat("solde_initial"));

            comptesBancaireList.add(comptesBancaire);
        }
    } catch (SQLException e) {
        e.printStackTrace();
    }
    return comptesBancaireList;
       }
       }
