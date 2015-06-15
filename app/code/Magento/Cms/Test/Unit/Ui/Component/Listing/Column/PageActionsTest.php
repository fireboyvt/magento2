<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Cms\Test\Unit\Ui\Component\Listing\Column;

use Magento\Cms\Ui\Component\Listing\Column\PageActions;

class PageActionsTest extends \PHPUnit_Framework_TestCase
{
    public function testPrepareItemsByPageId()
    {
        $pageId = 1;
        // Create Mocks and SUT
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        /** @var \PHPUnit_Framework_MockObject_MockObject $urlBuilderMock */
        $urlBuilderMock = $this->getMockBuilder('Magento\Framework\UrlInterface')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Magento\Cms\Ui\Component\Listing\Column\PageActions $model */
        $model = $objectManager->getObject(
            'Magento\Cms\Ui\Component\Listing\Column\PageActions',
            [
                'urlBuilder' => $urlBuilderMock,
            ]
        );

        // Define test input and expectations
        $items = [
            'data' => [
                'items' => [
                    [
                        'page_id' => $pageId
                    ]
                ]
            ]
        ];
        $name = 'item_name';
        $expectedItems = [
            [
                'page_id' => $pageId,
                $name => [
                    'edit' => [
                        'href' => 'test/url/edit',
                        'label' => __('Edit'),
                    ],
                    'delete' => [
                        'href' => 'test/url/delete',
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                        ],
                    ]
                ],
            ]
        ];

        // Configure mocks and object data
        $urlBuilderMock->expects($this->any())
            ->method('getUrl')
            ->returnValueMap(
                [
                    PageActions::CMS_URL_PATH_EDIT, ['page_id' => $pageId], 'test/url/edit',
                    PageActions::CMS_URL_PATH_DELETE, ['page_id' => $pageId], 'test/url/delete',
                ]
            );

        $model->setName($name);
        $model->prepareDataSource($items);
        // Run test
        $this->assertEquals($expectedItems, $items['data']['items']);
    }
}
