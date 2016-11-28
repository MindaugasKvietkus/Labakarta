<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AdminUserEditVariables;
use AppBundle\Entity\AdminUserSearchVariables;
use AppBundle\Entity\CreateUserVariables;
use AppBundle\Entity\DatabaseOffertolearn;
use AppBundle\Entity\DatabaseUserVariables;
use AppBundle\Entity\RememberVariables;
use AppBundle\Entity\ResetVariables;
use AppBundle\Entity\UserEditVariables;
use AppBundle\Entity\UserFileVariables;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\LoginVariables;
use Symfony\Component\PropertyAccess\Tests\Fixtures\TypeHinted;

class MainController extends Controller
{

    /**
     * @Route ("/", name="home")
     */

    public function index(){

        return $this->render("default/index.html.twig");
    }

    /**
     * @Route("/login", name="login123")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/test.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/prisijungti", name="login")
     */
    public function indexAction(Request $request)
    {
        $login = new LoginVariables();

        $form = $this->createForm('AppBundle\Form\LoginForm', $login);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                return $this->redirectToRoute("home");
            }
        }
        return $this->render('default/login.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route ("/priminti_slaptazodi" ,name="remember_password")
     */

    public function RememberPassword(Request $request){

        $remember_pass = new RememberVariables();

        $form = $this->createForm('AppBundle\Form\RememberForm', $remember_pass);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                    'email' => $remember_pass->email
                ));

                $message = \Swift_Message::newInstance()
                    ->setSubject("Slaptažodis")
                    ->setFrom("mjndauqas@gmail.com")
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView("Email/registration.html.twig", array(
                            'password' => $user->getPassword(),
                            'user' => $user->getNameSurname(),
                            'id' => $user->getId()
                        )),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
                return $this->redirectToRoute("home");
            }
        }

        return $this->render("default/remember_pass.hml.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route ("/registruotis", name="register")
     */

    public function Register(Request $request){

        $register = new CreateUserVariables();

        $form = $this->createForm('AppBundle\Form\CreateUserForm', $register);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                $user = new DatabaseUserVariables();

                $user->setNameSurname($register->name_surname);
                $user->setEmail($register->email);
                $user->setPassword($register->password);
                $user->setImage("NeraImage");
                //$user->setGroup("NeraGroup");
                //$user->setDate(new \DateTime('today'));

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

                $picture = "/uploaded_images/".$user->getId()."/default_picture.jpg";
                $user->setImage($picture);
                $em->persist($user);
                $em->flush();

                $fs = new Filesystem();

                $source_image = $this->getParameter("default_picture");
                $destination_image = $this->getParameter("set_picture").$user->getId();
                $fs->mirror($source_image, $destination_image);

                /*
                $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                   'id' => 'DESC'
                ));

                return $this->render("default/group.hml.twig", array(
                   'id' => $user->getId()
                ));
                */
                return $this->redirectToRoute("home");

            }
        }

        return $this->render("default/create_user.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/reset/{id}", name="reset")
     */

    public function ResetPass(Request $request, $id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
           'id' => $id
        ));

        $reset = new ResetVariables();

        $form = $this->createForm("AppBundle\Form\ResetForm", $reset);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                $user->setPassword($reset->password);

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);

                $em->flush();

                return $this->redirectToRoute("home");
            }
        }

        return $this->render("default/reset.html.twig", array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route ("{id}/what_you_do_want_learn", name="learn")
     */

    public function LearnAll($id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
           'id' => $id
        ));

        $categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findAll();

        return $this->render("default/learn.html.twig", array(
            'categories' => $categories,
            'id' => $id
        ));
    }

    /**
     * @Route ("{id}/what_you_do_want_learn/{category}", name="learn_sub")
     */

    public function LearnCategory($id, $category){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findOneBy(array(
           'category' => $category
        ));

        $sub_categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseSubCategory")->findBy(array(
            'category_id' => $categories->getId()
        ));

        return $this->render("default/learn_sub.html.twig", array(
            'categories' => $sub_categories,
            'user_id' => $id,
            'category' => $category
        ));
    }

    /**
     * @Route(path="/{id}/what_you_do_want_learn/{category}/{sub_category}", name="search_to_learn")
     */

    public function SearchToLearn($id, $category, $sub_category){

        $sub_category_get_id = $this->getDoctrine()->getRepository("AppBundle:DatabaseSubCategory")->findOneBy(array(
            'category' => $sub_category
        ));

        $sub_category_find_all = $this->getDoctrine()->getRepository("AppBundle:DatabaseOffertolearn")->findBy(array(
           'sub_category_id' => $sub_category_get_id->getId()
        ));

        return $this->render("default/learn_sub_search.html.twig", array(
           'offer_to_learn' => $sub_category_find_all,
            'sub_category' => $category
        ));
    }

    /**
     *
     */

    /**
     * @Route ("{id}/what_i_can_teach", name="teach")
     */

    public function TeachAll($id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
           'id' => $id
        ));

        $categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findAll();

        return $this->render("default/teach.html.twig", array(
            'categories' => $categories,
            'id' => $id
        ));
    }

    /**
     * @Route ("{id}/what_i_can_teach/{category}", name="teach_sub")
     */

    public function TeachCategory($id ,$category){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findOneBy(array(
            'category' => $category
        ));


        $sub_categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseSubCategory")->findBy(array(
            'category_id' => $categories->getId()
        ));

        return $this->render("default/teach_sub.html.twig", array(
            'name' => $categories->getCategory(),
            'categories' => $sub_categories,
            'id' => $id,
        ));
    }

    /**
     * @Route(path="/{id}/what_i_can_teach/{category}/{sub_category}", name="add_what_i_can_teach")
     */

    public function AddTeach($id, $category, $sub_category){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
           'id' => $id
        ));

        $category_get = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findOneBy(array(
            'category' => $category
        ));

        $sub_category_get = $this->getDoctrine()->getRepository("AppBundle:DatabaseSubCategory")->findOneBy(array(
            'category' => $sub_category
        ));

        $offer_to_learn = new DatabaseOffertolearn();

        $offer_to_learn->setSubCategoryId($sub_category_get);
        $offer_to_learn->setUserId($user);
        $em = $this->getDoctrine()->getManager();

        $em->persist($offer_to_learn);

        $em-> flush();

        return $this->redirect("/user/$id");

    }

    /**
     * @Route(path="/delete/sub_category/{id}")
     */

    public function DeleteSubCategory($id){

        $sub_category = $this->getDoctrine()->getRepository("AppBundle:DatabaseOffertolearn")->findOneBy(array(
            'sub_category_id' => $id
        ));
        $user_id = $sub_category->getUserId()->getId();
        $em = $this->getDoctrine()->getManager();

        $em->remove($sub_category);
        $em->flush();

        return $this->redirect("/user/$user_id");

    }

    /**
     * @Route(path="/user/{id}", name="user")
     */

    public function Profile($id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $offer = $this->getDoctrine()->getRepository("AppBundle:DatabaseOffertolearn")->findBy(array(
           'user_id' => $id
        ));

        return $this->render("default/user.html.twig", array(
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
            'picture' => $user->getImage(),
            'teach' => "",
            'test' => $user,
            'offer' => $offer
        ));
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */

    public function EditProfile(Request $request, $id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $user_edit = new UserEditVariables();

        $user_edit->name_surname = $user->getNameSurname();
        $user_edit->email = $user->getEmail();

        $form = $this->createForm('AppBundle\Form\UserEditForm', $user_edit);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                if ($user_edit->old_password === $user->getPassword()){

                    $em = $this->getDoctrine()->getManager();
                    $em->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                       'id' => $id
                    ));

                    $user->setNameSurname($user_edit->name_surname);
                    $user->setEmail($user_edit->email);
                    $user->setPassword($user_edit->new_password);

                    $em->flush();

                    return $this->redirectToRoute("home");
                }else{
                    echo "Blogas dabartinis slaptazodis";
                }
            }
        }

        return $this->render("default/user_edit.html.twig", array(
            'form' => $form->createView(),
            'picture' => $user->getImage(),
            'id' => $user->getId(),
        ));

    }

    /**
     * @Route(path="/user/edit/image/{id}", name="edit_image")
     */

    public function EditImage(Request $request, $id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $user_image = new UserFileVariables();

        $form = $this->createForm('AppBundle\Form\UserFileForm', $user_image);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                    'id' => $id
                ));
                $fs = new Filesystem();
                $delete = $this->getParameter("set_picture").$user->getId();
                $fs->remove($delete);
                $image_name = $user_image->file->getClientOriginalName();
                $user_image->file->move($this->getParameter("temp_folder"), $image_name);

                $destination_image_path = $this->getParameter("set_picture").$user->getId()."/";
                $fs->mirror($this->getParameter("temp_folder"), $destination_image_path);
                $fs->remove($this->getParameter("temp_folder"));

                $picture = "/uploaded_images/".$user->getId()."/".$user_image->file->getClientOriginalName();
                $user->setImage($picture);

                $em->flush();

            }
        }

        return $this->render("default/user_image.html.twig",array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @Route (path="/admin/user-list", name="admin_user_list")
     */

    public function AdminUserList(Request $request){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findAll();

        $search_name_surname = new AdminUserSearchVariables();

        $form = $this->createForm('AppBundle\Form\AdminUserSearchForm', $search_name_surname);

        if ($request->getMethod() === 'POST'){

            if ($form->isValid()){

                //$search_name_surname->name_surname =
                $this->redirect("/");

                exit;
                $data = $form->getData();
                dump($data);




                $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                    'name_surname' => $search_name_surname->name_surname,
                    'email' => $search_name_surname->email
                ));

                return $this->redirectToRoute('search');

                /*
                return $this->render("default/admin_search_name_surname.html.twig.html.twig", array(
                    'form' => $form->createView(),
                    'user' => $user
                ));
                */
            }
        }

        return $this->render("default/admin_user_list.html.twig", array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route (path="/admin/user-list/name", name="search")
     */

    public function adminUserSearch(){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'name_surname' => ""
        ));

        return $this->render("default/admin_search_name_surname.html.twig", array(
            'name' => $user->getName()
        ));
    }

    /**
     * @Route (path="/admin/user/{id}", name="admin_edit_user")
     */

    public function AdminEditUser (Request $request, $id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $user_form = new AdminUserEditVariables();

        $user_form->name_surname = $user->getNameSurname();
        $user_form->email = $user->getEmail();
        $user_form->old_password = $user->getPassword();

        $form = $this->createForm('AppBundle\Form\AdminUserEditForm', $user_form);

        if ($request->getMethod() === 'POST'){

            $form->handleRequest($request);

            if ($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                    'id' => $id
                ));

                $user->setNameSurname($user_form->name_surname);
                $user->setEmail($user_form->email);
                $user->setPassword($user_form->new_password);

                $em->flush();

                return $this->redirectToRoute("home");

            }
        }

        return $this->render("default/admin_user_edit.html.twig", array(
            'form' => $form->createView(),
            'picture' => $user->getImage(),
            'id'=> $id
        ));

    }

    /**
     * @Route(path="/admin/user/edit/image/{id}", name="admin_edit_image")
     */

    public function AdminEditImage(Request $request, $id)
    {

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $user_image = new UserFileVariables();

        $form = $this->createForm('AppBundle\Form\UserFileForm', $user_image);

        if ($request->getMethod() === 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
                    'id' => $id
                ));
                $fs = new Filesystem();
                $delete = $this->getParameter("set_picture") . $user->getId();
                $fs->remove($delete);
                $image_name = $user_image->file->getClientOriginalName();
                $user_image->file->move($this->getParameter("temp_folder"), $image_name);

                $destination_image_path = $this->getParameter("set_picture") . $user->getId() . "/";
                $fs->mirror($this->getParameter("temp_folder"), $destination_image_path);
                $fs->remove($this->getParameter("temp_folder"));

                $picture = "/uploaded_images/" . $user->getId() . "/" . $user_image->file->getClientOriginalName();
                $user->setImage($picture);

                $em->flush();

            }
        }

        return $this->render("default/admin_user_image.html.twig",array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @Route(path="/admin/user/delete/{id}", name="admin_user_delete")
     */

    public function AdminUserDelete($id){

        $user = $this->getDoctrine()->getRepository("AppBundle:DatabaseUserVariables")->findOneBy(array(
            'id' => $id
        ));

        $em = $this->getDoctrine()->getManager();

        $em->remove($user);

        $em->flush();

        return $this->redirectToRoute("admin_user_list");
    }

    /**
     * @Route(path="/admin/categories", name="admin_categories")
     */

    public function adminCategories (){

        $categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseCategory")->findAll();

        $sub_categories = $this->getDoctrine()->getRepository("AppBundle:DatabaseSubCategory")->findAll();

        $categories_id = "";
        /*
        foreach ($categories as $value){
            $categories_id = $value->getId();
            echo $value->getCategory();
            foreach ($sub_categories as $sub_value){
                if ($categories_id = $sub_value->getCategoryId()){

                }
            }
        }
        */
        return $this->render("default/admin_categories.html.twig", array(
            'categories' => $categories,
            'subcategories' => $sub_categories,
        ));
    }
}
