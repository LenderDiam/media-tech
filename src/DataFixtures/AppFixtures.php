<?php

namespace App\DataFixtures;

use App\Entity\{
    Audio,
    Author,
    Basket,
    Book,
    Copy,
    Document,
    Loan,
    Membership,
    Notification,
    Periodical,
    Publisher,
    Subscription,
    Transaction,
    User,
    Video
};
use App\Enum\{
    AudioFormat,
    BasketState,
    CopyPhysicalCondition,
    CopyState,
    PeriodicalFrequency,
    SubscriptionPeriodicity,
    VideoFormat,
};

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;

class AppFixtures extends Fixture
{

    // symfony console doctrine:fixtures:load -n.
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        for ($i = 0; $i < 9; $i++) {
            $user = new User();

            $user
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->unique()->safeEmail)
                ->setPassword(password_hash('password', PASSWORD_BCRYPT))
                ->setRoles(['ROLE_USER'])
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        $admin = new User();

        $admin
            ->setFirstname('admin')
            ->setLastname('admin')
            ->setEmail('admin@example.com')
            ->setPassword(password_hash('admin', PASSWORD_BCRYPT))
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);
        $users[] = $admin;
        $manager->flush();

        $subscriptions = [];
        for ($i = 0; $i < 3; $i++) {
            $sub = new Subscription();

            $sub
                ->setName($faker->word)
                ->setPrice($faker->randomFloat(0, 5, 30))
                ->setPeriodicity($faker->randomElement([SubscriptionPeriodicity::Monthly, SubscriptionPeriodicity::Yearly]))
            ;

            $manager->persist($sub);
            $subscriptions[] = $sub;
        }
        $manager->flush();

        $transactions = [];
        foreach ($users as $user) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $transaction = new Transaction();

                $transaction
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                    ->setUser($user)
                    ->setSubscription($faker->randomElement($subscriptions))
                    ->setAmount($transaction->getSubscription()->getPrice())
                ;

                $manager->persist($transaction);
                $transactions[] = $transaction;
            }
        }
        $manager->flush();

        $documents = [];
        for ($i = 0; $i < 20; $i++) {
            $docType = $faker->randomElement(['book', 'audio', 'video', 'periodical']);
            switch ($docType) {
                case 'book':
                    $doc = new Book();

                    $doc
                        ->setPages($faker->numberBetween(100, 500))
                        ->setIsbn($faker->isbn13)
                    ;

                    break;
                case 'audio':
                    $doc = new Audio();

                    $doc
                        ->setFormat($faker->randomElement([
                            AudioFormat::Undefined,
                            AudioFormat::Mp3,
                            AudioFormat::Wav,
                            AudioFormat::Flac,
                            AudioFormat::Other
                        ]))
                        ->setDuration(\DateTime::createFromFormat('H:i:s', $faker->time('H:i:s')))
                    ;

                    break;
                case 'video':
                    $doc = new Video();

                    $doc
                        ->setFormat($faker->randomElement([
                            VideoFormat::Undefined,
                            VideoFormat::Mp4,
                            VideoFormat::Avi,
                            VideoFormat::Mkv,
                            VideoFormat::Other
                        ]))
                        ->setDuration(\DateTime::createFromFormat('H:i:s', $faker->time('H:i:s')))
                    ;

                    break;
                case 'periodical':
                    $doc = new Periodical();

                    $doc
                        ->setFrequency($faker->randomElement([
                            PeriodicalFrequency::Undefined,
                            PeriodicalFrequency::Daily,
                            PeriodicalFrequency::Weekly,
                            PeriodicalFrequency::Monthly,
                            PeriodicalFrequency::Yearly,
                            PeriodicalFrequency::Other
                        ]));

                    break;
            }

            $doc
                ->setTitle($faker->sentence(3))
                ->setLangage($faker->randomElement(['fr', 'en']))
                ->setThumbnailUrl($faker->imageUrl())
                ->setPublicationDate($faker->dateTimeBetween('-10 years', 'now'))
            ;

            $manager->persist($doc);
            $documents[] = $doc;
        }

        $authors = [];
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();

            $author
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
            ;

            $manager->persist($author);
            $authors[] = $author;
        }

        foreach ($documents as $doc) {
            for ($i = 0; $i < rand(1, 2); $i++) {
                $doc->addAuthor($faker->randomElement($authors));
            }
        }

        $publishers = [];
        for ($i = 0; $i < 5; $i++) {
            $publisherType = $faker->randomElement(['company', 'individual']);

            $publisher = new Publisher();

            switch ($publisherType) {
                case 'company':

                    $publisher
                        ->setName($faker->company);

                    break;
                case 'individual':

                    $publisher
                        ->setFirstname($faker->firstName)
                        ->setLastname($faker->lastName)
                    ;

                    break;
            }

            $manager->persist($publisher);
            $publishers[] = $publisher;
        }

        foreach ($documents as $doc) {
            $doc->addPublisher($faker->randomElement($publishers));
        }
        $manager->flush();

        $loans = [];
        foreach ($users as $user) {
            $loan = new Loan();

            $loan
                ->setStartAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 days', 'now')))
                ->setWithdrawalAt($faker->optional()->dateTimeBetween('-8 days', 'now') ? DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-8 days', 'now')) : null)
                ->setBackAt($faker->optional()->dateTimeBetween('-7 days', 'now') ? DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-7 days', 'now')) : null)
                ->setUser($user)
            ;

            $manager->persist($loan);
            $loans[] = $loan;
        }
        $manager->flush();

        $copies = [];
        foreach ($documents as $doc) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $copy = new Copy();

                $copy
                    ->setDocument($doc)
                    ->setState($faker->randomElement([
                        CopyState::Available,
                        CopyState::Reserved,
                        CopyState::Borrowed,
                        CopyState::Lost,
                    ]))
                    ->setPhysicalCondition($faker->randomElement([
                        CopyPhysicalCondition::New,
                        CopyPhysicalCondition::Good,
                        CopyPhysicalCondition::Worn,
                        CopyPhysicalCondition::Damaged,
                    ]))
                    ->setLoan($faker->optional()->randomElement($loans))
                ;
                $manager->persist($copy);
                $copies[] = $copy;
            }
        }
        $manager->flush();

        $baskets = [];
        foreach ($users as $user) {
            $basket = new Basket();

            $basket
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                ->setState($faker->randomElement([
                    BasketState::Pending,
                    BasketState::Validated,
                    BasketState::Cancelled,
                ]))
                ->setUser($user)
            ;

            $manager->persist($basket);

            for ($i = 0; $i < rand(1, 3); $i++) {
                $basket->addCopy($faker->randomElement($copies));
            }

            $baskets[] = $basket;
        }
        $manager->flush();

        foreach ($users as $user) {
            $membership = new Membership();

            $membership
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                ->setDocument($faker->text(100))
                ->setUser($user)
            ;

            $manager->persist($membership);
        }
        $manager->flush();

        $notifications = [];
        for ($i = 0; $i < 10; $i++) {
            $notif = new Notification();

            $notif
                ->setObject($faker->sentence(3))
                ->setDescription($faker->paragraph)
                ->setSentAt($faker->optional()->dateTimeThisMonth() ? DateTimeImmutable::createFromMutable($faker->dateTimeThisMonth()) : null)

            ;

            $manager->persist($notif);
            $notifications[] = $notif;
        }
        $manager->flush();

        foreach ($users as $user) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $user->addNotification($faker->randomElement($notifications));
            }
        }

        foreach ($documents as $doc) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $doc->addUser($faker->randomElement($users));
            }
        }

        $manager->flush();
    }
}
