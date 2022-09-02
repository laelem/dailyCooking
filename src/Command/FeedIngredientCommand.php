<?php

namespace App\Command;

use App\Entity\Ingredient;
use App\Entity\IngredientCategory;
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
        $data = file_get_contents(__DIR__.'/../Feed/Ingredients.txt');
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

        $ingredients = $this->entityManager->getRepository(Ingredient::class)->findAll();
        foreach ($ingredients as $ingredient) {
            $this->entityManager->remove($ingredient);
        }
        $this->entityManager->flush();
        $categories = $this->entityManager->getRepository(IngredientCategory::class)->findAll();
        foreach ($categories as $category) {
            $this->entityManager->remove($category);
        }
        $this->entityManager->flush();

        $categoryRepo = $this->entityManager->getRepository(IngredientCategory::class);
        foreach ($ingredientsData as $ingredientRow) {
            $ingredient = (new Ingredient())->setName($ingredientRow['name']);
            $category1 = $categoryRepo->findOneBy(['name' => $ingredientRow['category1'], 'category1' => null]);
            if (!$category1) {
                $category1 = (new IngredientCategory())->setName($ingredientRow['category1']);
                $this->entityManager->persist($category1);
                $this->entityManager->flush();
            }
            $ingredient->setCategory1($category1);
            if (isset($ingredientRow['category2'])) {
                $category2 = $categoryRepo->findOneBy([
                    'name' => $ingredientRow['category2'],
                    'category1' => $category1,
                ]);
                if (!$category2) {
                    $category2 = (new IngredientCategory())->setName($ingredientRow['category2'])
                        ->setCategory1($category1);
                    $this->entityManager->persist($category2);
                    $this->entityManager->flush();
                }
                $ingredient->setCategory2($category2);
            }
            if (isset($ingredientRow['category3'])) {
                $category3 = $categoryRepo->findOneBy([
                    'name' => $ingredientRow['category3'],
                    'category1' => $category1,
                    'category2' => $category2,
                ]);
                if (!$category3) {
                    $category3 = (new IngredientCategory())->setName($ingredientRow['category3'])
                        ->setCategory1($category1)
                        ->setCategory2($category2);
                    $this->entityManager->persist($category3);
                    $this->entityManager->flush();
                }
                $ingredient->setCategory3($category3);
            }
            $this->entityManager->persist($ingredient);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}