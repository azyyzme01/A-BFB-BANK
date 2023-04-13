/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi.entities;

/**
 *
 * @author ANAS
 */
public class Transaction {
     private int id;
     private int compte_source_id;
     String nom;
    String prenom;
    String email;
    int num_tlfn;
    int compte_destination;
    float montant ;

    public Transaction(int id, int compte_source_id, String nom, String prenom, String email, int num_tlfn, int compte_destination, float montant) {
        this.id = id;
        this.compte_source_id = compte_source_id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.num_tlfn = num_tlfn;
        this.compte_destination = compte_destination;
        this.montant = montant;
    }

    public Transaction(int compte_source_id, String nom, String prenom, String email, int num_tlfn, int compte_destination, float montant) {
        this.compte_source_id = compte_source_id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.num_tlfn = num_tlfn;
        this.compte_destination = compte_destination;
        this.montant = montant;
    }

    public Transaction() {
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getCompte_source_id() {
        return compte_source_id;
    }

    public void setCompte_source_id(int compte_source_id) {
        this.compte_source_id = compte_source_id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public int getNum_tlfn() {
        return num_tlfn;
    }

    public void setNum_tlfn(int num_tlfn) {
        this.num_tlfn = num_tlfn;
    }

    public int getCompte_destination() {
        return compte_destination;
    }

    public void setCompte_destination(int compte_destination) {
        this.compte_destination = compte_destination;
    }

    public float getMontant() {
        return montant;
    }

    public void setMontant(float montant) {
        this.montant = montant;
    }

    @Override
    public String toString() {
        return "Transaction{" + "id=" + id + ", compte_source_id=" + compte_source_id + ", nom=" + nom + ", prenom=" + prenom + ", email=" + email + ", num_tlfn=" + num_tlfn + ", compte_destination=" + compte_destination + ", montant=" + montant + '}';
    }

   
    
}
