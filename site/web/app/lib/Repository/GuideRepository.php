<?php
namespace Repository;

use Entity\Guide;

final class GuideRepository extends AbstractRepository {
  protected static $customPostType = \App::POST_TYPE_GUIDE;

  protected static function getEntity(
    \WP_Post $post,
    bool $includeRelated = false
  ): Guide {
    $ghid = (new Guide($post->ID))
      ->setTitle($post->post_title, $post->ID)
      ->setNume(get_field('nume_eveniment', $post->ID))
      ->setInainteaEvenimentului(get_field('inaintea_evenimentului', $post->ID))
      ->setInTimpulEvenimentului(get_field('in_timpul_evenimentului', $post->ID))
      ->setDupaEveniment(get_field('dupa_eveniment', $post->ID))
      ->setInformatiiAditionale(get_field('informatii_aditionale', $post->ID))
      ->setVideoAjutator(get_field('video_ajutator', $post->ID))
      ->setGalerieFoto(static::falseyToNull(
        get_field('galerie_foto', $post->ID)
      ))
      ->setGuidePDF(static::falseyToNull(
        get_field('ghid_pdf', $post->ID)
      ))
      ->setPictograma(get_field('pictograma_eveniment', $post->ID))
      ->setPermalink(get_the_permalink($post->ID));
    if ($includeRelated) {
        $ghid->setSimilarGuides(self::getSimilarGuides());
    }

    return $ghid;
  }

  private static function getSimilarGuides(): array {
    $relatedPosts = get_field('ghiduri_similare');
    if (!$relatedPosts) {
      return [];
    }

    $ghiduriSimilare = [];
    foreach ($relatedPosts as $relatedPost) {
      $ghiduriSimilare[] = self::getEntity($relatedPost, false);
    }

    return $ghiduriSimilare;
  }
}
