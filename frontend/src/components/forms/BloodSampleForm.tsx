'use client';

import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { apiClient } from '@/services/apiClient';
import { Loader2 } from 'lucide-react';
import { bloodGroups } from '@/lib/bloodCompatibility';

const schema = z.object({
  blood_group: z.string().refine((val) => bloodGroups.includes(val as any), { message: 'Select a valid blood group' }),
  units_available: z.number().min(1, 'Units must be at least 1'),
  expiry_date: z.string().min(1, 'Expiry date is required'),
});

type FormValues = z.infer<typeof schema>;

export default function BloodSampleForm({ onSuccess, onCancel }: { onSuccess: () => void, onCancel: () => void }) {
  const [error, setError] = useState('');

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<FormValues>({
    resolver: zodResolver(schema),
    defaultValues: {
      units_available: 1,
    }
  });

  const onSubmit = async (data: FormValues) => {
    try {
      setError('');
      await apiClient.post('/blood-samples', data);
      onSuccess();
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to add blood sample');
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4 bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
      <h3 className="text-lg font-medium text-gray-900 mb-4">Add Blood Sample</h3>
      
      {error && (
        <div className="bg-red-50 text-red-600 p-3 rounded-md text-sm border border-red-100">
          {error}
        </div>
      )}
      
      <div className="grid grid-cols-1 gap-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">Blood Group</label>
          <select
            {...register('blood_group')}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-2 border bg-white"
          >
            <option value="">Select a group</option>
            {bloodGroups.map((bg) => (
              <option key={bg} value={bg}>{bg}</option>
            ))}
          </select>
          {errors.blood_group && <p className="mt-1 text-xs text-red-600">{errors.blood_group.message}</p>}
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Units Available (Pints/Bags)</label>
          <input
            type="number"
            {...register('units_available', { valueAsNumber: true })}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-2 border"
          />
          {errors.units_available && <p className="mt-1 text-xs text-red-600">{errors.units_available.message}</p>}
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Expiry Date</label>
          <input
            type="date"
            {...register('expiry_date')}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-2 border"
          />
          {errors.expiry_date && <p className="mt-1 text-xs text-red-600">{errors.expiry_date.message}</p>}
        </div>
      </div>

      <div className="pt-4 flex gap-3">
        <button
          type="button"
          onClick={onCancel}
          className="flex-1 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
        >
          Cancel
        </button>
        <button
          type="submit"
          disabled={isSubmitting}
          className="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-70"
        >
          {isSubmitting ? <Loader2 className="animate-spin h-5 w-5" /> : 'Save Sample'}
        </button>
      </div>
    </form>
  );
}
