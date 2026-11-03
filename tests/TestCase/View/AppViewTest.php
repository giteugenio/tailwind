<?php
declare(strict_types=1);

namespace App\Test\TestCase\View;

use App\View\AppView;
use ArrayObject;
use Cake\Datasource\Paging\PaginatedResultSet;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

class AppViewTest extends TestCase
{
    protected AppView $view;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadRoutes();
        $this->loadPlugins(require CONFIG . 'plugins.php');
        $request = new ServerRequest([
            'webroot' => '/',
            'base' => '',
            'url' => '/pages/home',
            'params' => ['controller' => 'Pages', 'action' => 'display', 'plugin' => null],
        ]);
        Router::setRequest($request);
        $this->view = new AppView($request);
    }

    public function testLayoutLoadsLocalAssetsAndEscapesFlashMessages(): void
    {
        $session = $this->view->getRequest()->getSession();
        $session->write('Flash.flash', [[
            'message' => '<script>alert(1)</script>',
            'params' => ['type' => 'success'],
        ]]);
        $html = $this->view->renderLayout('<p>Application content</p>');

        $this->assertStringContainsString('/css/app.css', $html);
        $this->assertStringContainsString('/tailwind_ui/js/dismiss.js', $html);
        $this->assertStringNotContainsString('cdn.jsdelivr.net', $html);
        $this->assertStringNotContainsString('milligram', $html);
        $this->assertStringContainsString('alert-success', $html);
        $this->assertStringContainsString('data-tailwind-ui-dismiss', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringContainsString('<p>Application content</p>', $html);
        $this->assertNull($session->read('Flash.flash'));
    }

    public function testControllerFlashMessagesUseDaisyUi(): void
    {
        foreach (['success', 'error', 'warning', 'info'] as $type) {
            $this->view->getRequest()->getFlash()->{$type}('Saved <strong>message</strong>');
            $html = $this->view->Flash->render();
            $this->assertStringContainsString('alert-' . $type, $html);
            $this->assertStringContainsString('data-tailwind-ui-dismiss', $html);
            $this->assertStringContainsString('&lt;strong&gt;message&lt;/strong&gt;', $html);
            $this->assertStringNotContainsString('onclick=', $html);
        }
    }

    public function testFormKeepsLabelsValidationAndCsrfProtection(): void
    {
        $request = $this->view->getRequest()->withAttribute('csrfToken', 'test-csrf-token');
        $this->view->setRequest($request);
        $context = [
            'schema' => ['title' => ['type' => 'string']],
            'errors' => ['title' => ['required' => 'Title is required']],
        ];
        $form = $this->view->Form;
        $html = $form->create($context);
        $html .= $form->control('title');
        $html .= $form->button('Save');
        $html .= $form->end();

        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('Title is required', $html);
        $this->assertStringContainsString('<legend class="fieldset-legend">Title</legend>', $html);
        $this->assertStringContainsString('name="_csrfToken"', $html);
        $this->assertStringContainsString('test-csrf-token', $html);
        $this->assertStringContainsString('btn', $html);
    }

    public function testPaginationUsesDaisyUiAndMarksCurrentPage(): void
    {
        $this->view->Paginator->setPaginated(new PaginatedResultSet(new ArrayObject(), [
            'alias' => 'Articles',
            'scope' => null,
            'count' => 10,
            'totalCount' => 30,
            'perPage' => 10,
            'pageCount' => 3,
            'currentPage' => 2,
            'hasPrevPage' => true,
            'hasNextPage' => true,
            'start' => 11,
            'end' => 20,
            'sortDefault' => false,
            'directionDefault' => false,
            'completeSort' => [],
        ]));
        $html = $this->view->Paginator->links();

        $this->assertStringContainsString('class="join"', $html);
        $this->assertStringContainsString('join-item btn', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('rel="next"', $html);
    }
}
