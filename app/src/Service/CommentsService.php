<?php
/**
 * Comments service.
 */

namespace App\Service;

use App\Entity\Comments;
use App\Entity\Photos;
use App\Entity\Users;
use App\Repository\CommentsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentsService.
 */
class CommentsService implements CommentsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;
    /**
     * Comments repository.
     */
    private CommentsRepository $commentsRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CommentsRepository $commentsRepository Comments repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->commentsRepository = $commentsRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentsRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Paginated list by users.
     *
     * @param int    $page   Page
     * @param Photos $photos Photos
     *
     * @return PaginationInterface Paginator interface
     */
    public function getPaginatedListByPhotos(int $page, Photos $photos): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentsRepository->findBy(
                ['photo' => $photos]
            ),
            $page,
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Paginated list by users.
     *
     * @param int   $page Page
     * @param Users $user Users
     *
     * @return PaginationInterface Paginator interface
     */
    public function getPaginatedListByUser(int $page, Users $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentsRepository->findBy(
                ['Users' => $user]
            ),
            $page,
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Getter for photos.
     *
     * @param Comments $comments Comments
     *
     * @return Photos Photos
     */
    public function getPhotos(Comments $comments): ?Photos
    {
        return $comments->getPhoto();
    }

    /**
     * Save entity.
     *
     * @param Comments $comments Comments entity
     */
    public function save(Comments $comments): void
    {
        if (null === $comments->getId()) {
            $comments->setPostDate(new \DateTimeImmutable());
        }
        $this->commentsRepository->save($comments);
    }

    /**
     * Delete entity.
     *
     * @param Comments $comments Photos entity
     */
    public function delete(Comments $comments): void
    {
        $this->commentsRepository->delete($comments);
    }
}
