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
public class comptesBancaire {
    int id;
    String nom;
    String prenom;
    String email;
    int num_tlfn;
    float solde_initial;

    public comptesBancaire(String nom, String prenom, String email, int num_tlfn, float solde_initial) {
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.num_tlfn = num_tlfn;
        this.solde_initial = solde_initial;
    }

    public comptesBancaire(int id, String nom, String prenom, String email, int num_tlfn, float solde_initial) {
        this.id = id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.num_tlfn = num_tlfn;
        this.solde_initial = solde_initial;
    }

    public comptesBancaire() {
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    @Override
    public String toString() {
        return "comptesBancaire{" + "id=" + id + ", nom=" + nom + ", prenom=" + prenom + ", email=" + email + ", num_tlfn=" + num_tlfn + ", solde_initial=" + solde_initial + '}';
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

    public float getSolde_initial() {
        return solde_initial;
    }

    public void setSolde_initial(float solde_initial) {
        this.solde_initial = solde_initial;
    }
    
    
}
