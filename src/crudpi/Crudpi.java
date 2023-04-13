/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package crudpi;

import crudpi.entities.comptesBancaire;
import crudpi.services.compteBancaireCRUD;
import crudpi.utils.connexion;

/**
 *
 * @author ANAS
 */
public class Crudpi {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        
        //connexion mc = new connexion();
        //comptesBancaire compte = new comptesBancaire("Anas","Dridi","anas@anas.com",525252552,0.0f);
        compteBancaireCRUD cb = new compteBancaireCRUD();
        
        //cb.addEntity(compte);
        System.out.println(cb.entitiesList());
        
        
    }
    
}
