<?php

namespace App\Command;

use App\Entity\Ingredient;
use App\Entity\IngredientCategory;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:feed:feed-ingredient')]
class FeedIngredientCommand extends Command
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = file_get_contents(__DIR__.'/../Feed/Ingredients_v2.txt');
        $rows = explode(PHP_EOL, $data);

        $res = [];
        foreach ($rows as $row) {
            $rowData = explode('- ', $row);
            $pos = (strlen($rowData[0])/2)+1;
            $name = str_replace('"', '', $rowData[1]);
            $res[] = [
                'pos'  => $pos,
                'name' => trim($name),
            ];
        }

        $ingredientsData = [];
        $category1 = null;
        $category2 = null;
        $category3 = null;

        foreach ($res as $index => $row) {
            if (strpos($row['name'], ':') === strlen($row['name'])-1) {
                $category = substr($row['name'], 0, -1);
            } else {
                $category = $row['name'];
            }
            if ($row['pos'] === 1) {
                $category1 = $category;
            }
            if ($row['pos'] === 2) {
                $category2 = $category;
            }
            if ($row['pos'] === 3) {
                $category3 = $category;
            }
            if (!isset($res[$index+1])) {
                $ingredientsData[] = [
                    'name' => $row['name'],
                    'category1' => $category1,
                    'category2' => $row['pos'] > 2 ? $category2 : null,
                    'category3' => $row['pos'] > 3 ? $category3 : null,
                ];
                break;
            }
            if ($res[$index+1]['pos'] > $row['pos']) {
                continue;
            }
            $ingredientsData[] = [
                'name' => $row['name'],
                'category1' => $category1,
                'category2' => $row['pos'] > 2 ? $category2 : null,
                'category3' => $row['pos'] > 3 ? $category3 : null,
            ];
        }

        $this->removeAll();

        $categoryRepo = $this->entityManager->getRepository(IngredientCategory::class);
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        $cptCategory = 1;

        foreach ($ingredientsData as $ingredientRow) {
            $ingredient = (new Ingredient())->setName($ingredientRow['name']);

            $category = $categoryRepo->findOneBy(['name' => $ingredientRow['category1']]);
            if (!$category) {
                $category = (new IngredientCategory())
                    ->setName($ingredientRow['category1'])
                    ->setPosition($cptCategory)
                ;
                $cptCategory++;
                $this->entityManager->persist($category);
            }
            $ingredient->setCategory($category);

            if ($ingredientRow['category2']) {
                $category2 = $tagRepo->findOneBy(['name' => $ingredientRow['category2']]);
                if (!$category2) {
                    $category2 = (new Tag())->setName($ingredientRow['category2']);
                    $this->entityManager->persist($category2);
                }
                $ingredient->addTag($category2);
            }

            if ($ingredientRow['category3']) {
                $category3 = $tagRepo->findOneBy(['name' => $ingredientRow['category3']]);
                if (!$category3) {
                    $category3 = (new Tag())->setName($ingredientRow['category3']);
                    $this->entityManager->persist($category3);
                }
                $ingredient->addTag($category3);
            }

            $this->entityManager->persist($ingredient);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }

    public function removeAll()
    {
        $ingredients = $this->entityManager->getRepository(Ingredient::class)->findAll();
        foreach ($ingredients as $ingredient) {
            $this->entityManager->remove($ingredient);
        }
        $categories = $this->entityManager->getRepository(IngredientCategory::class)->findAll();
        foreach ($categories as $category) {
            $this->entityManager->remove($category);
        }
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        foreach ($tags as $tag) {
            $this->entityManager->remove($tag);
        }
        $this->entityManager->flush();
    }
}