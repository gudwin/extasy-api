<?php
namespace Extasy\API\tests\Domain\Validator;

use Extasy\API\tests\BaseTest;
use Extasy\API\tests\SampleRequest;
use Extasy\API\Domain\Validators\FieldsValidator;


class FieldsValidatorTest extends BaseTest
{
    public function testValidation() {
        $testsData = $this->getSampleTestData();
        foreach ( $testsData as $testData ) {
            $fields = isset( $testData['fields'] ) ? $testData['fields'] : [];
            $files = isset( $testData['files'] ) ? $testData['files'] : [];
            $validator = new FieldsValidator( $fields, $files);
            $request = new SampleRequest( $testData['request']);

            $errorMsg = sprintf('%s:%s:%s', print_r( $fields, true), print_r($files, true),print_r( $testData['request'], true ));
            try {
                $validator->validate( $request );
                if ( !$testData['result'] ) {
                    $this->fail( $errorMsg );
                }
            } catch ( \Exception $e) {
                if ( $testData['result']) {
                    $this->fail( $errorMsg );
                }
            }

        }
    }
    protected function getSampleTestData() {
        $abcParams = [
            'a' => 1,
            'b' => 1,
            'c' => 1,
        ];
        $efFiles = [
            'e' => 1,
            'f' => 1
        ];
        $testsData = [
            [
                'request' => [],
                'fields' => [],
                'result' => true
            ],
            [
                'request' => [],
                'fields' => ['a','b'],
                'result' => false
            ],
            [
                'request' => [
                    'params' => $abcParams,
                ],
                'fields' => ['a','b'],
                'result' => true
            ],
            [
                'request' => [
                    'params' => $abcParams,
                ],
                'fields' => ['a', 'b', 'c', 'd'],
                'result' => false
            ],

            [
                'request' => [],
                'files' => ['a','b'],
                'result' => false
            ],
            [
                'request' => [
                    'files' => $efFiles,
                ],
                'files' => ['e','f'],
                'result' => true
            ],
            [
                'request' => [
                    'files' => $efFiles,
                ],
                'fields' => ['e','f','g'],
                'result' => false
            ],
            [
                'request' => [
                    'params' => $abcParams,
                    'files' => $efFiles,
                ],
                'fields' => ['a'],
                'files' => ['e'],
                'result' => true,
            ]
        ];
        return $testsData;
    }
}