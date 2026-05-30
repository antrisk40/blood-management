export const bloodGroups = [
  'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
] as const;

export type BloodGroup = typeof bloodGroups[number];

const compatibilityMap: Record<BloodGroup, BloodGroup[]> = {
  'O-': ['O-'],
  'O+': ['O-', 'O+'],
  'A-': ['O-', 'A-'],
  'A+': ['O-', 'O+', 'A-', 'A+'],
  'B-': ['O-', 'B-'],
  'B+': ['O-', 'O+', 'B-', 'B+'],
  'AB-': ['O-', 'A-', 'B-', 'AB-'],
  'AB+': ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+']
};

/**
 * Checks if a receiver is compatible with a given donor blood sample.
 * @param receiverBloodGroup The blood group of the receiver.
 * @param donorBloodGroup The blood group of the donor sample.
 * @returns true if compatible.
 */
export function isCompatible(receiverBloodGroup: BloodGroup, donorBloodGroup: BloodGroup): boolean {
  const allowedDonors = compatibilityMap[receiverBloodGroup];
  return allowedDonors.includes(donorBloodGroup);
}
