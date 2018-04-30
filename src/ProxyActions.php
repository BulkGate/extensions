<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class ProxyActions extends SmartObject
{
    /** @var IO\IConnection */
    private $connection;

    /** @var IModule */
    private $module;

    /** @var Synchronize */
    private $synchronize;

    /** @var ISettings */
    private $settings;

    /** @var Translator */
    private $translator;

    public function __construct(IO\IConnection $connection, IModule $module, Synchronize $synchronize, ISettings $settings, Translator $translator)
    {
        $this->connection = $connection;
        $this->module = $module;
        $this->synchronize = $synchronize;
        $this->settings = $settings;
        $this->translator = $translator;
    }

    public function login(array $data)
    {
        $response = $this->connection->run(new IO\Request($this->module->getUrl('/module/sign/in'), $data));

        $login = (array) $response->get('::login');

        if(isset($login['application_id']) && isset($login['application_token']))
        {
            $this->settings->set('static:application_id', $login['application_id'], array('type' => 'int'));
            $this->settings->set('static:application_token', $login['application_token']);
            return isset($login['application_token_temp']) ? $login['application_token_temp'] : 'guest';
        }
        return $response;
    }

    public function logout()
    {
        $this->settings->delete('static:application_token');
    }

    public function register(array $data)
    {
        $response = $this->connection->run(new IO\Request($this->module->getUrl('/module/sign/up'), $data));

        $register = (array) $response->get('::register');

        if(isset($register['application_id']) && isset($register['application_token']))
        {
            $this->settings->set('static:application_id', $register['application_id'], array('type' => 'int'));
            $this->settings->set('static:application_token', $register['application_token']);
            return isset($register['application_token_temp']) ? $register['application_token_temp'] : 'guest';
        }
        return $response;
    }

    public function authenticate()
    {
        return $this->connection->run(new IO\Request($this->module->getUrl('/widget/authenticate')));
    }

    public function saveSettings(array $settings)
    {
        if(isset($settings['delete_db']))
        {
            $this->settings->set('main:delete_db', $settings['delete_db'], array('type' => 'int'));
        }

        if(isset($settings['language']))
        {
            $this->translator->setLanguage($settings['language']);
        }
    }

    public function saveCustomerNotifications(array $data)
    {
        $self = $this;

        return $this->synchronize->synchronize(function($module_settings) use ($self, $data)
        {
            return $self->connection->run(new IO\Request($self->module->getUrl('/module/hook/customer'),
                array("__synchronize" => $module_settings) + $data,
                true
            ));
        });
    }

    public function saveAdminNotifications(array $data)
    {
        $self = $this;

        return $this->synchronize->synchronize(function($module_settings) use ($self, $data)
        {
            return $self->connection->run(new IO\Request($self->module->getUrl('/module/hook/admin'),
                array("__synchronize" => $module_settings) + $data,
                true
            ));
        });
    }

    public function loadCustomersCount($id, $type = 'load', array $data = array())
    {
        switch ($type)
        {
            case 'addFilter':
                return $this->connection->run(new IO\Request($this->module->getUrl('/module/sms-campaign/add-filter/'.(int)$id), $data));
                break;
            case 'removeFilter':
                return $this->connection->run(new IO\Request($this->module->getUrl('/module/sms-campaign/remove-filter/'.(int)$id), $data));
                break;
            case 'load':
            default:
                return $this->connection->run(new IO\Request($this->module->getUrl('/module/sms-campaign/load/'.(int)$id)));
                break;
        }
    }

    public function saveModuleCustomers($id, array $data)
    {
        return $this->connection->run(new IO\Request($this->module->getUrl('/module/sms-campaign/save/'.(int) $id), array(
            'customers' => $data
        )));
    }
}
