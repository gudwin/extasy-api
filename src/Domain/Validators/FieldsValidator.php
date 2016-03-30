<?php
namespace Extasy\API\Domain\Validators;

use Extasy\API\Domain\Core\AbstractValidator;
use Extasy\API\Domain\Exceptions\ValidateException;
use Extasy\API\Infrastructure\IO\AbstractRequest;

class FieldsValidator implements AbstractValidator
{
    /**
     * @var \Extasy\API\Domain\Validators\FieldsValidatorConfig
     */
    protected $config = null;
    public function __construct( FieldsValidatorConfig $config  )
    {
        $this->config = $config;
    }
    public function validate() {
        try {
            $currentField = '';
            foreach ( $this->config->fields as $row ) {
                $currentField = $row;
                $this->config->request->getParam( $row );
            }
            foreach ( $this->config->files as $row ) {
                $currentField = $row;
                $this->config->request->getFile( $row );
            }
        } catch (\Exception $e ) {
            $error = sprintf( 'Unable to find field `%s` in request', $currentField );
            throw new ValidateException( $error );
        }

    }
}