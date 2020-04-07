<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends AbstractController
{
    /**
     * @Route("/organisation", name="organisation")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrganisationController.php',
        ]);
    }

    /**
     * @Route("/getOrganisation", name="getOrganisation")
     */

     public function getOrganisation(){

        $value = Yaml::parseFile('../public/organizations.yaml');

         return $this->json([
             'status' => 200,
             'data' => $value
         ]);
    }

    /**
     * @Route("/createOrg", methods="POST")
     */

    public function create(Request $request){
        // $request = $this->transformJsonBody($request);

        // $name = $request->firstname;

        $data = json_decode(
            $request->getContent(),
            true
        );

        if(!$data['name']){
            return $this->json([
                'status' => 422,
                'message' => 'organisation name not found'
            ]);
        }

        if(!$data['description']){
            return $this->json([
                'status' => '422',
                'message' => 'organisation description not found'
            ]);
        }

        $value = Yaml::parseFile('../public/organizations.yaml');

        
       

        $newData = array(
            // "organizations"=> array(
                // array(
                    "name"=> $data['name'],
                    "description"=> $data['description'],
                    "users" => array()
                //   ),
            //   ),
        );

        $newValue = array_push($value['organizations'], $newData);
        $yamlnew = Yaml::dump($value, 3,2, Yaml::DUMP_OBJECT);
        


        file_put_contents('../public/organizations.yaml', $yamlnew);


        return $this->json([
            'status' => 200,
            'message' => 'You have succesfully added an organisation'
        ]);
    }

    /**
     * @Route("/addUser/{name}", methods="POST")
     */
    public function addUser($name,Request $request){
        
        $data = json_decode(
            $request->getContent(),
            true
        );
        $value = Yaml::parseFile('../public/organizations.yaml');
        // $colors = array_column($value);

        $searchedArray = array_search($name, array_column($value['organizations'], 'name'));

        $newData = array(
            "name"=> $data['name'],
            "role"=> $data['role'],
            "password" => $data['password']
             
        );

       
        $newValue = array_push($value['organizations'][$searchedArray]['users'], $newData);
        $yamlnew = Yaml::dump($value, 3,2, Yaml::DUMP_OBJECT);
        


        file_put_contents('../public/organizations.yaml', $yamlnew);


        return $this->json([
            'status' => 200,
            'message' => 'User has been succesfully added'
        ]);
    }

    /**
     * @Route("/deleteOrganisation/{name}", methods="DELETE")
     */

    public function delete($name){
        
        $value = Yaml::parseFile('../public/organizations.yaml');
        // $colors = array_column($value);

        $searchedArray = array_search($name, array_column($value['organizations'], 'name'));


         
        unset($value['organizations'][$searchedArray]);
        // $value = array_values($value);
        
        
        // $newValue = array('organizations' => $value);
        $yamlnew = Yaml::dump($value, 3,2, Yaml::DUMP_OBJECT);

        file_put_contents('../public/organizations.yaml', $yamlnew);


        // echo $deletedValue;

        return $this->json([
            'status' => 200,
            'message' => 'Delete was successfull'
        ]);
    }

    /**
     * @Route("/getSpecificOrg/{name}", methods="GET")
     */

    public function getSpecificOrganization($name){
        $value = Yaml::parseFile('../public/organizations.yaml');
       

        $searchedArray = array_search($name, array_column($value['organizations'], 'name'));

        $specificData = $value['organizations'][$searchedArray];

        return $this->json([
            'status' => 200,
            'data' => $specificData
        ]);
    }

    /**
     * @Route("/editpecificOrg/{name}", methods="PUT")
     */

    public function editOrg($name, Request $request){
        $data = json_decode(
            $request->getContent(),
            true
        );
        $value = Yaml::parseFile('../public/organizations.yaml');
       

        $searchedArray = array_search($name, array_column($value['organizations'], 'name'));
      

        $array = $value['organizations'];
            
            
        
        foreach ($array as $key => $item) {
           
            $item["name"] = $data['name'];
            $item["description"] = $data['description'];
            $array[$searchedArray] = $item;
           
        }

        $newdata = array('organizations' => $array);


        $yamlnew = Yaml::dump($newdata, 3,2, Yaml::DUMP_OBJECT);

        file_put_contents('../public/organizations.yaml', $yamlnew);


        return $this->json([
            'status' => 200,
            'message' => 'Organization update was succesfull',
          
        ]);
    }

  
  
}
