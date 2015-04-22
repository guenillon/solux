<?php
namespace JPI\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\MappedSuperclass
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class Entity
{
	
	/**
	 * @ORM\Column(name="createdAt", type="datetime", nullable=true)
	 * @Exclude
	 */
	protected $createdAt;
	
	/**
	 * @ORM\Column(name="createdBy", type="integer", nullable=true)
	 * @Exclude
	 */
	protected $createdBy;
	
	/**
	 * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
	 * @Exclude
	 */
	protected $updatedAt;
	
	/**
	 * @ORM\Column(name="updatedBy", type="integer", nullable=true)
	 * @Exclude
	 */
	protected $updatedBy;
	
	/**
	 * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
	 * @Exclude
	 */
	protected $deletedAt;

	public function getAttributes($arrayTab)
	{
		$lMembres = get_object_vars($this);
		$valeurTab = array();
		foreach($lMembres as $lCle => $lValeur) {
			if( in_array($lCle, $arrayTab) )
			{
				$valeurTab[$lCle] = $lValeur;
			}
		}
		return $valeurTab;
	}
	
	/**
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 * @return User
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	
		return $this;
	}
	
	/**
	 * Get createdAt
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	/**
	 * Set createdBy
	 *
	 * @param integer $createdBy
	 * @return User
	 */
	public function setCreatedBy($createdBy)
	{
		$this->createdBy = $createdBy;
	
		return $this;
	}
	
	/**
	 * Get createdBy
	 *
	 * @return integer
	 */
	public function getCreatedBy()
	{
		return $this->createdBy;
	}
	
	/**
	 * Set deletedAt
	 *
	 * @param \DateTime $deletedAt
	 * @return User
	 */
	public function setDeletedAt($deletedAt)
	{
		$this->deletedAt = $deletedAt;
	
		return $this;
	}
	
	/**
	 * Get deletedAt
	 *
	 * @return \DateTime
	 */
	public function getDeletedAt()
	{
		return $this->deletedAt;
	}
	
	/**
	 * Set updatedAt
	 *
	 * @param \DateTime $updatedAt
	 * @return User
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
	
		return $this;
	}
	
	/**
	 * Get updatedAt
	 *
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
	/**
	 * Set updatedBy
	 *
	 * @param integer $updatedBy
	 * @return User
	 */
	public function setUpdatedBy($updatedBy)
	{
		$this->updatedBy = $updatedBy;
	
		return $this;
	}
	
	/**
	 * Get updatedBy
	 *
	 * @return integer
	 */
	public function getUpdatedBy()
	{
		return $this->updatedBy;
	}
}