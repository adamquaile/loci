# Loci

*This tool is still in development stage. Use with caution, and be restrictive with the version you choose in `composer` so you don't break stuff when you run `composer update`.*

Tool for rapidly building in-memory repository classes in PHP. Particularly useful
when building automated tests of your domain layer.

**Related: [Modelling by example](http://everzet.com/post/99045129766/introducing-modelling-by-example)**

## Installation

Install with composer, using package `adamquaile/loci`. 

## Usage

Imagine your domain layer calls for a repository class to store widgets. Its interface
looks like this:

    interface WidgetRepository
    {
        function findByWidgetID(WidgetID $id);
        function remove(Widget $widget);
    }
    
You can quickly make a repository class to match this repository using the base class
`BaseInMemoryRepository`.

    class InMemoryWidgetRepository extends BaseInMemoryRepository implements WidgetRepository
    {
        function findByWidgetID(WidgetID $id)
        {
            return $this->objects->findBy(array('widgetID' => $id));
        }
        
        function remove(Widget $widget)
        {
            $this->objects->remove($widget);
        }
    
    }
    
`$this->objects` is a protected `ObjectRepository` class. It's API is still unstable, so
please look at `spec/AdamQuaile/Loci/ObjectRepositorySpec.php` for how it behaves.
