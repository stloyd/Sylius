<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\PromotionRuleInterface;
use Sylius\Component\Promotion\Generator\Instruction;
use Sylius\Component\Promotion\Model\ActionInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;

/**
 * Default promotion fixtures.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class LoadPromotionsData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $promotion = $this->createPromotion(
            'PR1',
            'New Year',
            'New Year Sale for 3 and more items.',
            [$this->createRule(PromotionRuleInterface::TYPE_ITEM_COUNT, ['count' => 3, 'equal' => true])],
            [$this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, ['amount' => 500])]
        );

        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'PR2',
            'Christmas',
            'Christmas Sale for orders over 100 EUR.',
            [$this->createRule(PromotionRuleInterface::TYPE_ITEM_TOTAL, ['amount' => 10000, 'equal' => true])],
            [$this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, ['amount' => 250])]
        );

        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'PR3',
            '3rd order',
            'Discount for 3rd order',
            [$this->createRule(PromotionRuleInterface::TYPE_NTH_ORDER, ['nth' => 3])],
            [$this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, ['amount' => 500])]
        );

        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'PR4',
            'Shipping to Germany',
            'Discount for orders with shipping country Germany',
            [$this->createRule(PromotionRuleInterface::TYPE_SHIPPING_COUNTRY, ['country' => $this->getReference('Sylius.Country.DE')->getId()])],
            [$this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, ['amount' => 500])]
        );

        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'PM5',
            'Gift cards',
            'Simple gift cards with amount 50 EUR.',
            array(),
            array($this->createAction(ActionInterface::TYPE_GIFT_CARD_DISCOUNT, array())),
            true
        );

        $manager->persist($promotion);

        $manager->flush();

        $this->generateCoupons($promotion, 5, CouponInterface::TYPE_GIFT_CARD, null, 5000);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * Create promotion rule of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     *
     * @return PromotionRuleInterface
     */
    protected function createRule($type, array $configuration)
    {
        /** @var $rule PromotionRuleInterface */
        $rule = $this->getPromotionRuleFactory()->createNew();
        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }

    /**
     * Create promotion action of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     *
     * @return ActionInterface
     */
    protected function createAction($type, array $configuration)
    {
        /** @var $action ActionInterface */
        $action = $this->getPromotionActionFactory()->createNew();
        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }

    /**
     * Create promotion with set of rules and actions.
     *
     * @param string $code
     * @param string $name
     * @param string $description
     * @param array  $rules
     * @param array  $actions
     * @param bool   $couponBased
     *
     * @return PromotionInterface
     */
    protected function createPromotion($code, $name, $description, array $rules, array $actions, $couponBased = false)
    {
        /** @var $promotion PromotionInterface */
        $promotion = $this->getPromotionFactory()->createNew();
        $promotion->setName($name);
        $promotion->setDescription($description);
        $promotion->setCouponBased($couponBased);
        $promotion->setCode($code);

        foreach ($rules as $rule) {
            $promotion->addRule($rule);
        }
        foreach ($actions as $action) {
            $promotion->addAction($action);
        }

        $this->setReference('Sylius.Promotion.'.$name, $promotion);

        return $promotion;
    }

    /**
     * Generate coupons for given promotion.
     *
     * @param PromotionInterface $promotion
     * @param int                $amount
     * @param string             $type
     * @param null|int           $limit
     * @param int                $value
     */
    protected function generateCoupons($promotion, $amount, $type, $limit = null, $value = 0)
    {
        $instruction = new Instruction();
        $instruction->setAmount($amount);
        $instruction->setType($type);
        $instruction->setValue($value);
        $instruction->setUsageLimit($limit);

        $generator = $this->get('sylius.generator.promotion_coupon');
        $generator->generate($promotion, $instruction);
    }
}
