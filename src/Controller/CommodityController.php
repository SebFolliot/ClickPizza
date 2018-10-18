<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\Commodity;
use ClickPizza\Form\Type\CommodityType;

class CommodityController 
{
    
    /**
     * Add a product controller
     *
     * @param Application $app Silex application
     */
    public function addCommodityAction (Request $request, Application $app) {
        $commodity = new Commodity();
        $commodityForm = $app['form.factory']->create(CommodityType::class, $commodity);
        $commodityForm->handleRequest($request);
        if ($commodityForm->isSubmitted() && $commodityForm->isValid()) {
            $directory = __DIR__.'/../../web/images/upload';
            $file = $commodityForm['picture']->getData();
            $file->move($directory, $file->getClientOriginalName());
            $commodity->setPicture($file->getClientOriginalName());
            $app['dao.commodity']->update($commodity);
            $app['session']->getFlashBag()->add('success', 'Le produit a été mis à jour sur la carte.');
        }
        return $app['twig']->render('commodity_form.html.twig', array(
        'title' => 'Ajout d\'un produit',
        'commodityForm' => $commodityForm->createView()));
    }
    
    /**
     * Update a product controller
     *
     * @param integer $id Commodity id
     * @param Application $app Silex application
     */    
    public function editCommodityAction ($id, Request $request, Application $app) {
        $commodity = $app['dao.commodity']->commodityId($id);
        $commodityForm = $app['form.factory']->create(CommodityType::class, $commodity);
        $commodityForm->handleRequest($request);
        if ($commodityForm->isSubmitted() && $commodityForm->isValid()) {
            $directory = __DIR__.'/../../web/images/upload';
            $file = $commodityForm['picture']->getData();
            $file->move($directory, $file->getClientOriginalName());
            $commodity->setPicture($file->getClientOriginalName());
            $app['dao.commodity']->update($commodity);
            $app['session']->getFlashBag()->add('success', 'Le produit a été mis à jour avec succès.');
        }
        return $app['twig']->render('commodity_form.html.twig', array(
            'title' => 'Modifier le produit',
            'commodityForm' => $commodityForm->createView()));
    }
    
    /**
     * Delete a product controller
     *
     * @param integer $id Commodity id
     * @param Application $app Silex application
     */
    public function deleteCommodityAction ($id, Request $request, Application $app) {
        
        if (($app['dao.orderCommodity']->orderCommodityCommodityId($id) !== null)) {
            $app['session']->getFlashBag()->add('warning', 'Ce produit ne peut être supprimé car il est rattaché à des commandes existantes.');
        } else {
            $app['dao.commodity']->delete($id);
            $app['session']->getFlashBag()->add('success', 'Le produit a été supprimé de la base de données.');
        }
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}