<?php
namespace Extasy\API\Domain\Validators;

use Extasy\API\Domain\Core\AbstractValidator;
use Extasy\API\Domain\Exceptions\ValidateException;
use Extasy\API\Infrastructure\IO\AbstractRequest;

class FieldsValidator implements AbstractValidator
{
    protected $fields = null;
    protected $files = null;
    public function __construct( array $fields, array $files = []  )
    {
        $this->fields = $fields;
        $this->files = $files;
    }
    public function validate(AbstractRequest $request ) {
        try {
            foreach ( $this->fields as $row ) {
                $currentField = $row;
                $request->getParam( $row );
            }
            foreach ( $this->files as $row ) {
                $currentField = $row;
                $request->getFile( $row );
            }
        } catch (\Exception $e ) {
            $error = sprintf( 'Unable to find field `%s` in request', $currentField );
            throw new ValidateException( $error );
        }

    }
}