<?php
namespace BlogBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('content');
        $formMapper->add('author');
        $formMapper->add('posts');
        $formMapper->add('date');
    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
      $datagridMapper->add('content');
      $datagridMapper->add('author');
      $datagridMapper->add('posts');
      $datagridMapper->add('date');
    }
    protected function configureListFields(ListMapper $listMapper)
    {
      $listMapper->add('content');
      $listMapper->add('author');
      $listMapper->add('posts');
      $listMapper->add('date');
    }
}
